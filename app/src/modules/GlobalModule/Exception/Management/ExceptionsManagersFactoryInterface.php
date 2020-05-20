<?php

    namespace App\GlobalModule\Exception\Management;

    /**
     * Creating exceptions managers.
     * 
     * @see ExceptionsManagerInterface For description of exceptions managers
     */
    interface ExceptionsManagersFactoryInterface
    {
        /**
         * Returns appropriate exceptions manager instance.
         * 
         * @param $relatedModuleFullName Full name of module (class/trait/interface) 
         *     related with requested exceptions manager. If null, global exceptions manager will be returned
         */
        public function getExceptionsManager(?string $relatedModuleFullName = null): ExceptionsManagerInterface;
    }