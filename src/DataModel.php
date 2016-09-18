<?php

namespace OpCache;

class DataModel
{
    private $_configuration;
    private $_status;
    private $_d3Scripts = array();

    public function __construct()
    {
        $this->_configuration = opcache_get_configuration();
        $this->_status = opcache_get_status();
    }

    public function getPageTitle()
    {
        return 'PHP '.phpversion()." with OpCache {$this->_configuration['version']['version']}";
    }

    public function getStatusDataRows()
    {
        $rows = array();
        foreach ($this->_status as $key => $value) {
            if ($key === 'scripts') {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($k === 'current_wasted_percentage' || $k === 'opcache_hit_rate') {
                        $v = number_format($v, 2).'%';
                    }
                    if ($k === 'blacklist_miss_ratio') {
                        $v = number_format($v, 2).'%';
                    }
                    if ($k === 'start_time' || $k === 'last_restart_time') {
                        $v = ($v ? date(DATE_RFC822, $v) : 'never');
                    }
                    if (THOUSAND_SEPARATOR === true && is_int($v)) {
                        $v = number_format($v);
                    }

                    $rows[$k] = $v;
                }
                continue;
            }
            if ($value === false) {
                $value = 'false';
            }
            if ($value === true) {
                $value = 'true';
            }
            $rows[$key] = $value;
        }

        return $this->array_without($this->_status, 'scripts');
    }

    public function array_without($element)
    {
        if ($element instanceof \ArrayAccess) {
            $filtered_element = clone $element;
        } else {
            $filtered_element = $element;
        }
        $args = func_get_args();
        unset($args[0]);
        foreach ($args as $arg) {
            if (isset($filtered_element[$arg])) {
                unset($filtered_element[$arg]);
            }
        }

        return $filtered_element;
    }

    public function getConfigDataRows()
    {
        return $this->_configuration['directives'];
    }

    public function getScriptStatusRows()
    {
        foreach ($this->_status['scripts'] as $key => $data) {
            $dirs[dirname($key)][basename($key)] = $data;
            $this->_arrayPset($this->_d3Scripts, $key, array(
        'name' => basename($key),
        'size' => $data['memory_consumption'],
      ));
        }

        asort($dirs);

        $basename = '';
        while (true) {
            if (count($this->_d3Scripts) != 1) {
                break;
            }
            $basename .= DIRECTORY_SEPARATOR.key($this->_d3Scripts);
            $this->_d3Scripts = reset($this->_d3Scripts);
        }

        $this->_d3Scripts = $this->_processPartition($this->_d3Scripts, $basename);
        dump($dirs);

        foreach ($dirs as $dir => $files) {
            foreach ($files as $file => $data) {
                $rows[$dir][$file] = $data;
                $rows[$dir][$file]['file'] = basename($file);
            }
        }

        return $rows;
    }

    public function getScriptStatusCount()
    {
        return count($this->_status['scripts']);
    }

    public function getGraphDataSetJson()
    {
        $dataset = array();
        $dataset['memory'] = array(
      $this->_status['memory_usage']['used_memory'],
      $this->_status['memory_usage']['free_memory'],
      $this->_status['memory_usage']['wasted_memory'],
    );

        $dataset['keys'] = array(
      $this->_status['opcache_statistics']['num_cached_keys'],
      $this->_status['opcache_statistics']['max_cached_keys'] - $this->_status['opcache_statistics']['num_cached_keys'],
      0,
    );

        $dataset['hits'] = array(
      $this->_status['opcache_statistics']['misses'],
      $this->_status['opcache_statistics']['hits'],
      0,
    );

        $dataset['restarts'] = array(
      $this->_status['opcache_statistics']['oom_restarts'],
      $this->_status['opcache_statistics']['manual_restarts'],
      $this->_status['opcache_statistics']['hash_restarts'],
    );

        if (THOUSAND_SEPARATOR === true) {
            $dataset['TSEP'] = 1;
        } else {
            $dataset['TSEP'] = 0;
        }

        return $dataset;
    }

    public function getUsedMemory()
    {
        return $this->_status['memory_usage']['used_memory'];
    }

    public function getFreeMemory()
    {
        return $this->_status['memory_usage']['free_memory'];
    }

    public function getWastedMemory()
    {
        return $this->_status['memory_usage']['wasted_memory'];
    }

    public function getWastedMemoryPercentage()
    {
        return $this->_status['memory_usage']['current_wasted_percentage'];
    }

    public function getD3Scripts()
    {
        return $this->_d3Scripts;
    }

    private function _processPartition($value, $name = null)
    {
        if (array_key_exists('size', $value)) {
            return $value;
        }

        $array = array('name' => $name, 'children' => array());

        foreach ($value as $k => $v) {
            $array['children'][] = $this->_processPartition($v, $k);
        }

        return $array;
    }

    private function _format_value($value)
    {
        if (THOUSAND_SEPARATOR === true) {
            return number_format($value);
        } else {
            return $value;
        }
    }

  // Borrowed from Laravel
  private function _arrayPset(&$array, $key, $value)
  {
      if (is_null($key)) {
          return $array = $value;
      }
      $keys = explode(DIRECTORY_SEPARATOR, ltrim($key, DIRECTORY_SEPARATOR));
      while (count($keys) > 1) {
          $key = array_shift($keys);
          if (!isset($array[$key]) || !is_array($array[$key])) {
              $array[$key] = array();
          }
          $array = &$array[$key];
      }
      $array[array_shift($keys)] = $value;

      return $array;
  }
}
