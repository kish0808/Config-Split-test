<?php

namespace Drupal\d8_activity\Controller;


use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

class D8CustomController extends ControllerBase {

    public function content() {
        $build = [
        '#markup' => $this->t('Hello World!'),
        ];
        return $build;
    }

    public function dynamicContent($arg){

    return [
        '#markup' => $this->t("<p>Drupal-activiry </p>"."Dynamic argument Passed : @arg", [ '@arg' => $arg ]),
        ];
    }

    public function entityContent(Node $node) {
        return [
            '#markup' => $node->getTitle(),
        ];
    }

    public function multipleContent(NodeInterface $node1, NodeInterface $node2) {
        return [
            '#markup' => $node1->getTitle() . '====' . $node2->getTitle(),
        ];
    }



}