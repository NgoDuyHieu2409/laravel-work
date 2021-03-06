<?php
namespace App\Traits;

trait AppUtility
{
    private function getItemStringToArray($string = '')
    {
        $data = [];
        if($string){
            $array = explode(',', trim($string, ','));
            foreach($array as $v){
                $v = explode('-', $v);
                $data[trim($v[0])] = trim($v[1]);
            }
        }
        return array_unique($data);
    }

    private function getValueItemToArray($string = '', $key)
    {
        $data = $this->getItemStringToArray($string);
        return $data[$key];
    }

    private function getItemToArray($array, $keys)
    {
        if(!is_array($keys)){
            $keys = (array) $keys;
        }

        $data = [];
        foreach ($keys as $k) {
            $data[] = $array[$k];
        }
        
        return implode(', ', array_unique($data));
    }

    private function formatSearchFilters(array $filter)
    {
        return array_filter($filter, function ($item) {
            if (is_array($item)) {
                list($field, $condition, $val) = $item;
                return sizeof(array_filter(explode('%', $val)));
            }
            return isset($item);
        });
    }
}
