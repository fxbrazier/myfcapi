<?php

namespace AdminBundle\Helper\Form;

use Symfony\Component\Form\Form;

trait FormErrorsTraits
{
    /**
     * Returns all errors of children
     *
     * @param \Symfony\Component\Form\Form $form
     * @param                              $i
     *
     * @return array
     */
    public function getErrorMessages(Form $form, $i = 0)
    {
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            if ($error->getMessagePluralization() !== null) {
                $template = $this->get('translator')
                    ->transChoice(
                        $error->getMessage(),
                        $error->getMessagePluralization(),
                        $error->getMessageParameters()
                    );
            } else {
                $template = $this->get('translator')->trans($error->getMessage());
            }
            $errors[$key] = $template;
        }
        if ($i == 0 && !empty($errors)) {
            $tmpErrors      = $errors;
            $errors         = [];
            $errors['form'] = $tmpErrors;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $childErrors = $this->getErrorMessages($child, $i + 1);
                    if (!empty($childErrors)) {
                        $errors[$child->getName()] = $childErrors;
                    }
                }
            }
        }
        return $errors;
    }
}