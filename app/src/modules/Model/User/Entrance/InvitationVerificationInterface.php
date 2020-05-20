<?php

    namespace App\Model\User\Entrance;

    /** 
     * Verifying invitations for accounts registration.
     * 
     * Invitation is just secret string to allow account's registration.
     */
    interface InvitationVerificationInterface
    {
        public function whetherInvitationExists(string $invitationCode): bool;
        public function isInvitationDataCorrect(string $invitationCode): bool;
    }