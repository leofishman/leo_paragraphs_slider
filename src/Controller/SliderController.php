<?php
/**
 * @file
 * Contains \Drupal\leo_paragraphs_slider\Controller\SliderController.
 */
namespace Drupal\leo_paragraphs_slider\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;


/**
 * Implementing JSON api for sliders paragraph type.
 */
class sliderController extends ControllerBase {
    /**
     * Callback for the API.
     */
    public function renderJsonApi( $nid) {
        return new JsonResponse([
            'data' =>is_numeric($nid)?$this->getResults($nid):'no slider found',
            'method' => 'GET',
        ]);
    }
    /**
     * A helper function returning results.
     */
    private function getResults($nid) {

        $node = Node::load($nid);
        if (!empty($node)){
            $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $node->getType());
            $data = [];
            foreach ($definitions as $paragraph) {
                if (
                    $paragraph->getSetting('target_type')
                    && $paragraph->getSetting('target_type') == 'paragraph'
                    && in_array('slider',$paragraph->getSettings()['handler_settings']['target_bundles'])
                ) {
                    $sliders = $node->get('field_slider')->referencedEntities();
                    foreach ($sliders as $slider){
                        foreach ($slider->get('field_image')->referencedEntities() as $baner) {
                            $fid = $baner->get('fid')->value;
                            $file = \Drupal\file\Entity\File::load($fid);
                            $path = $file->url();
                        }

                        $data[] = ["title" => $slider->get('field_title')->value, "image" => $path, "body" =>  $slider->get('field_paragraph')->value];
                    }
                }
            }
        }

        return $data;
    }
}
