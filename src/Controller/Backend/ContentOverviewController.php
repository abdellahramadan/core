<?php

declare(strict_types=1);

namespace Bolt\Controller\Backend;

use Bolt\Configuration\Content\ContentType;
use Bolt\Controller\TwigAwareController;
use Bolt\Repository\ContentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ContentOverviewController extends TwigAwareController implements BackendZone
{
    /**
     * @Route("/content/{contentType}", name="bolt_content_overview")
     */
    public function overview(ContentRepository $contentRepository, Request $request, string $contentType = ''): Response
    {
        $contentType = ContentType::factory($contentType, $this->config->get('contenttypes'));

        $page = (int) $request->query->get('page', 1);
        $amountPerPage = $contentType->get('records_per_page', 10);

        if($request->query->get('sort')){
            $sortBy = $request->query->get('sort');
            dump($sortBy);
            // die();
            $records = $contentRepository->findForListing($page, $amountPerPage, $contentType, false, $sortBy);

            dump($request->query->get('sort'));
            // die();
        }else {
            $records = $contentRepository->findForListing($page, $amountPerPage, $contentType, false);
        }

        return $this->renderTemplate('@bolt/content/listing.html.twig', [
            'records' => $records,
            'contentType' => $contentType,
        ]);
    }
}
