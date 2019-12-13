<?php
namespace Sante\SpecialisteBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Discutea\DForumBundle\Entity\Post;

class CorrProfil
{
    /**
     * 
     * Control if user's is autorized to Edit profil
     *
     * @param int $idpost
     * @param int $id
     *
     * @return boolean
     */
    public function canEditProfil($idverif , $id)
    {

        if($idverif==$id)
        {return true;}
        else{return false;}
    }
}
