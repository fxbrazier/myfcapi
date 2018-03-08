<?php
namespace AdminBundle\Helper\Response;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class ApiResponse
 * @package AdminBundle\Helper\Response
 */
class ApiResponse extends Response
{
    /**
     * ApiResponse constructor.
     *
     * @param string $content
     * @param int    $status
     * @param array  $errors
     * @param array  $headers
     */
    public function __construct($content = '', $status = 200, $errors = [], array $headers = [])
    {
        $response         = new \stdClass();
        $response->result = $content;
        $response->errors = $errors;
        $headers = array_merge($headers, ['Content-Type' => 'application/json']);
        parent::__construct(json_encode($response), $status, $headers);
    }
}