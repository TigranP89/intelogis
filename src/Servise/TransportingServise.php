<?php
namespace Src\Servise;

class TransportingServise {
    public function fast_price($weight, $sourceKladr, $targetKladr)
    {
        try {
            $roadCoef = $this->roadCoef($sourceKladr, $targetKladr);
            // Implementation of shipping cost calculation depending on $weight, $sourceKladr, $targetKlad

            $price = 150 + ($weight * 50)  + ($roadCoef * 100);   
            // Return result in JSON
            return json_encode($price);
            

        } catch (\PDOException $e) {
            return json_encode($e->getMessage());
        }
    }

    public function roadCoef($sourceKladr, $targetKladr)
    {
        //The coefficient should be calculated taking into account the transportation distance

        $array = [1, 2, 3];

        $randomIndex  = array_rand($array, 1);
        $randConf = $array[$randomIndex];

        return $randConf;
    }

    public function slow_coefficient($weight, $sourceKladr, $targetKladr)
    {
        try {
            // Implementation of calculation of slow delivery coefficient depending on $weight, $sourceKladr, $targetKlad
            
            $roadCoef  = $this->roadCoef($sourceKladr, $targetKladr) - 1;

            $weightCoef =  $weight <= 1 ? 0 : $weight - 1;

            $coefficient = 1 + $weightCoef +  $roadCoef;
            
            // Return result in JSON
            return json_encode((int)$coefficient);
            

        } catch (\PDOException $e) {
            return json_encode($e->getMessage());
        }
    }

    public function fast_period($sourceKladr, $targetKladr)
    {
        try {
            // Implementation of calculation of delivery time (in days) depending on $sourceKladr, $targetKlad
            
            if($this->roadCoef($sourceKladr, $targetKladr) == 1){
                $period = 1;
            } else {
                $period = 2; 
            }
            // Return result in JSON
            return json_encode($period);
            
        } catch (\PDOException $e) {
            return json_encode($e->getMessage());
        }
    }

    public function slow_date($sourceKladr, $targetKladr)
    {
        try {
            // Implementation of delivery date calculation depending on $sourceKladr, $targetKlad
            $days = $this->roadCoef($sourceKladr, $targetKladr) + 1;
            $slow_date = date('Y-m-d', strtotime(date('Y-m-d') . ' +'. $days .' day'));

            // Return result in JSON
            return json_encode($slow_date);
        } catch (\PDOException $e) {
            return json_encode($e->getMessage());
        }
    }
}