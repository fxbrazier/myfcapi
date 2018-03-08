<?php

namespace AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use AdminBundle\Helper\Form\FormErrorsTraits;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
/**
 * POST Json handler controller
 */
class JsonController extends Controller
{
    use FormErrorsTraits;
    public static $fieldsApi = [];
    public static $fieldsApiAdmin = [];
    /**
     * Get JSON from POST
     *
     * @param Request $request
     *
     * @return ArrayCollection|null
     */
    public function getJson(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $content = $request->getContent();

            if (empty($content)) {
                throw new BadRequestHttpException("Content is empty");
            }
            if (!$this->isValidJsonString($content)) {
                throw new BadRequestHttpException("Content is not a valid json");
            }
            return new ArrayCollection(json_decode($content, true));
        } else {
            throw new BadRequestHttpException("Wrong request !");
        }
    }
    /**
     * Checks if JSON is valid
     *
     * @param $string
     *
     * @return boolean
     */
    private function isValidJsonString($string)
    {
        if (is_string($string) && strlen($string)) {
            json_decode($string);
            $kernel  = $this->get('kernel');
            $devMode = $kernel->isDebug();
            $noError = json_last_error() === JSON_ERROR_NONE;
            if ($devMode && !$noError) {
                throw new Exception("Wrong JSON received : " . json_last_error());
            } else {
                return $noError;
            }
        }
        return false;
    }
    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
    }
}