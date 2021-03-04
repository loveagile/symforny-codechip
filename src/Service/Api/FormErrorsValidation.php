<?php


namespace App\Service\Api;


use Symfony\Component\Form\FormInterface;

class FormErrorsValidation
{
    public function getErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach($form->all() as $childForm) {
            if($childForm instanceof FormInterface) {

                if($e = $this->getErrors($childForm)) {
                    $errors[$childForm->getName()] = $e;
                }
            }
        }

        return $errors;
    }
}