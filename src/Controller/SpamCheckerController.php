<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class SpamCheckerController extends AbstractController
{

    private const SPAM = ["Jhon@flop.e", "Benrnard@oafzafaz.e", "Tony@yup.e", "Ben@benitoben.e"];
    private const SPAM_DOMAINES = ["flop.e", "oafzafaz.e", "yup.e", "benitoben.e"];


    #[Route('/checker', name: 'api_spam_checker', methods: ['POST'])]
    public function check(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? '';

        if (in_array($email, self::SPAM))
        {
            return new JsonResponse(['result' => 'spam'], 200);
        }

        return new JsonResponse(['result' => 'ok'], 200);
    }




    #[Route('/checker2', name: 'api_check_email', methods: ['POST'])]
    public function check2(Request $request): JsonResponse
    {
        $data = $request->toArray();

        if(!isset($data['email']))
        {
            throw new BadRequestHttpException("L'email est obligatoire");
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
        {
            throw new UnprocessableEntityHttpException("L'email est incorect");
        }


        
        $email = $data['email'];
        $parts = explode("@", $email);
        $domain = $parts[1];
        if (in_array($domain, self::SPAM_DOMAINES))
        {
            return $this->json(['result' => 'spam']);
        }


        
        return $this->json(['result' => 'ok']);
    }
}
