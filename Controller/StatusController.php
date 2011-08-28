<?php

namespace SocietoPlugin\Societo\StatusPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateReference;

use Societo\BaseBundle\Util\ArrayAccessibleParameterBag;
use SocietoPlugin\Societo\StatusPlugin\Form\StatusType;

class StatusController extends Controller
{
    public function postAction($gadget)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->get('request');
        if ($request->getMethod() != 'POST') {
            throw $this->createNotFoundException();
        }

        $em = $this->get('doctrine.orm.entity_manager');

        $status = new \SocietoPlugin\Societo\StatusPlugin\Entity\Status();
        $form = $this->get('form.factory')->create(new StatusType($gadget->getParameter('max_length')), $status);
        $form->bindRequest($request);
        if ($form->isValid()) {
            $status = $form->getData();
            $status->setAuthor($user->getMember());

            $em->persist($status);
            $em->flush();

            $this->get('session')->setFlash('notice', 'Status was created successfully');
            $uri = $form->has('redirect_to') ? $form->get('redirect_to')->getData() : '_root';
            try {
                $uri = $this->generateUrl($uri);
            } catch (\InvalidArgumentException $e) {
                // do nothing
            }

            $this->get('session')->setFlash('success', 'Changes are saved successfully');

            return $this->redirect($uri);
        }

        return $this->render('SocietoPluginBundle:Gadget:only_gadget.html.twig', array(
            'gadget' => $gadget,
            'parent_attributes' => $this->get('request')->attributes,
            'parameters' => new ArrayAccessibleParameterBag(array('form' => $form)),
        ));
    }
}
