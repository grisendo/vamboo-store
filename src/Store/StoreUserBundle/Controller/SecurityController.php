<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  * @version
 */

namespace Store\StoreUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Annotation\Entity;

use Elcodi\UserBundle\Entity\Interfaces\CustomerInterface;
use Symfony\Component\Form\FormView;

/**
 * Class SecurityController
 */
class SecurityController extends Controller
{
    /**
     * Login page
     *
     * @param FormView $loginFormView Login form view
     *
     * @return array
     *
     * @Route(
     *      path = "/login",
     *      name = "store_login"
     * )
     * @Template
     *
     * @AnnotationForm(
     *      class = "store_user_form_types_login",
     *      name  = "loginFormView"
     * )
     */
    public function loginAction(FormView $loginFormView)
    {
        $customer = $this
            ->get('elcodi.core.user.wrapper.customer_wrapper')
            ->getCustomer();

        if ($customer instanceof CustomerInterface) {

            $this->redirect('store_home');
        }

        return [
            'form' => $loginFormView,
        ];
    }

    /**
     * Register page
     *
     * @param CustomerInterface $customer         empty customer
     * @param FormView          $registerFormView Register form type
     * @param boolean           $isValid          Form submition is valid
     *
     * @return array
     *
     * @Route(
     *      path = "/register",
     *      name = "store_register"
     * )
     * @Method({"GET", "POST"})
     * @Template
     *
     * @Entity(
     *      name          = "customer",
     *      factoryClass  = "elcodi.core.user.factory.customer",
     *      factoryMethod = "create",
     *      factoryStatic = false
     * )
     * @AnnotationForm(
     *      class         = "store_user_form_types_register",
     *      entity        = "customer",
     *      handleRequest = true,
     *      name          = "registerFormView",
     *      validate      = "isValid"
     * )
     */
    public function registerAction(
        CustomerInterface $customer,
        FormView $registerFormView,
        $isValid
    )
    {
        $registerFormView->
        var_dump($customer);die(var_dump($isValid));
        if ($isValid) {

            $this
                ->getDoctrine()
                ->getManager()
                ->flush($customer);

            $this
                ->get('elcodi.core.user.services.customer_manager')
                ->register($customer, 'secured_area');

            $this->redirect('store_home');
        }

        return [
            'form' => $registerFormView,
        ];
    }
}
 