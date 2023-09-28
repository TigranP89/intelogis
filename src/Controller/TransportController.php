<?php
namespace Src\Controller;

use Src\Servise\TransportingServise;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *  version="1.0.0",
 *  title="Transport Api Demo",
 *  description="Swagger Transport Api description",
 *  @OA\Contact(
 *  email="tigranpetrasyan@gmail.com"
 *  )
 * )
 */
class TransportController {
    private $requestMethod;
    private $type;
    private $servise;
    
    public function __construct($requestMethod, $type)
    {
        $this->requestMethod = $requestMethod;

        $this->servise = new TransportingServise();
        $this->type = $type;        
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                if($this->type === 'fast'){                    
                    $response = $this->fast();                    
                } else if($this->type === 'slow') {                  
                    $response = $this->slow();
                }
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * @OA\Post(
     * path="/delivery/fast",
     * summary="Method for calculating the cost of fast delivery.",
     * tags={"Fast_method"},
     * @OA\RequestBody(
     *  @OA\MediaType(
     *    mediaType="json",
     *    @OA\Schema(
     *       @OA\Property(
     *          property="weight",
     *          type="integer",
     *        ),
     *        @OA\Property(
     *          property="sourceKladr",
     *          type="string",
     *        ),
     *        @OA\Property(
     *          property="targetKladr",
     *          type="string",
     *        ),
     *      ),
     *    ),
     * ),
     * @OA\Response(
     *   response="200",
     *   description="Fast Delivery",
     * ),
     * @OA\Response(response="404", description="Not Found")
     * )
     */
    private function fast()
    {
        try {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validate($input)) {
                return $this->unprocessableEntityResponse();
            }
    
            $price = (float)$this->servise->fast_price($input['weight'], $input['sourceKladr'], $input['targetKladr']);
            $period = (int)$this->servise->fast_period($input['sourceKladr'], $input['targetKladr']);
            
            $result = [
                "price" => $price,
                "period" => $period,
                "error" => ""
            ];    
    
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
    
            return $response;
        } catch (\Exception $e) {
            $result = [
                "price" => "",
                "period" => "",
                "error" => $e->getMessage()
            ];    
    
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode($result);
    
            return $response;
        }

    }
    /**
     * @OA\Post(
     * path="/delivery/slow",
     * summary="Method for calculating the cost of slow delivery.",
     * tags={"Slow_method"},
     * @OA\RequestBody(
     *  @OA\MediaType(
     *    mediaType="json",
     *    @OA\Schema(
     *       @OA\Property(
     *          property="weight",
     *          type="integer",
     *        ),
     *        @OA\Property(
     *          property="sourceKladr",
     *          type="string",
     *        ),
     *        @OA\Property(
     *          property="targetKladr",
     *          type="string",
     *        ),
     *      ),
     *    ),
     * ),
     * @OA\Response(
     *   response="200",
     *   description="Slow Delivery",
     * ),
     * @OA\Response(response="404", description="Not Found")
     * )
     */
    private function slow()
    {
        try {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validate($input)) {
                return $this->unprocessableEntityResponse();
            }
    
            $coefficient = (float)$this->servise->slow_coefficient($input['weight'], $input['sourceKladr'], $input['targetKladr']);
            $date = $this->servise->slow_date($input['sourceKladr'], $input['targetKladr']);

            $result = [
                "coefficient" => $coefficient,
                "date" => $date,
                "error" => ""
            ];    
    
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
    
            return $response;
        } catch (\Exception $e) {
            $result = [
                "coefficient" => "",
                "date" => "",
                "error" => $e->getMessage()
            ];    
    
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode($result);
    
            return $response;
        }
    }


    private function validate($input)
    {
        if (! isset($input['weight'])) {
            return false;
        }

        if (isset($input['weight']) && ($input['weight'] <= 0 ||  empty($input['weight']))){
            return false;
        }

        if (! isset($input['sourceKladr'])) {
            return false;
        }
        
        if (isset($input['sourceKladr']) && empty($input['sourceKladr'])){
            return false;
        }

        if (! isset($input['targetKladr'])) {
            return false;
        }
        
        if (isset($input['targetKladr']) && empty($input['targetKladr'])){
            return false;
        }

        return true;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        return $response;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);

        return $response;
    }
}
