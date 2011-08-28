<?php

namespace SocietoPlugin\Societo\StatusPlugin\PageGadget;

use \Societo\PageBundle\PageGadget\AbstractPageGadget;
use \SocietoPlugin\Societo\StatusPlugin\Form\StatusType;

/**
 *
 * @author Kousuke Ebihara <ebihara@php.net>
 */
class StatusForm extends AbstractPageGadget
{
    protected $caption = 'Status form';

    protected $description = 'Form for posting new status';

    public function execute($gadget, $parentAttributes, $parameters)
    {
        $status = new \SocietoPlugin\Societo\StatusPlugin\Entity\Status();

        if ($parameters->has('form')) {
            $form = $parameters->get('form');
        } else {
            $form = $this->get('form.factory')->create(new StatusType($gadget->getParameter('max_length')), $status);
            $form['redirect_to']->setData($this->get('request')->getRequestUri());
        }

        return $this->render('SocietoStatusPlugin:PageGadget:status_form.html.twig', array(
            'gadget' => $gadget,
            'form' => $form->createView(),
        ));
    }

    public function getOptions()
    {
        return array(
            // TODO: unused
            'redirect_to' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),

            'max_length' => array(
                'type' => 'text',
                'options' => array(
                    'required' => false,
                ),
            ),

            'show_max_length' => array(
                'type' => 'choice',
                'options' => array(
                    'choices' => array(
                        '1' => 'Yes',
                        '0' => 'No',
                    ),
                ),
            ),
        );
    }
}
