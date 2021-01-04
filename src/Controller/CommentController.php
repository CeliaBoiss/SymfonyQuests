<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{id}", name="comment_delete", methods={"DELETE", "GET", "POST"})
     */
    public function delete(Comment $comment, Request $request): Response
    {        
        if (!($this->getUser() == $comment->getAuthor())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the author can delete the comment!');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}
