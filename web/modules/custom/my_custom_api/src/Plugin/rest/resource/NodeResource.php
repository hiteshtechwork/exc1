<?php

namespace Drupal\my_custom_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a REST API endpoint for nodes.
 *
 * @RestResource(
 *   id = "node_resource",
 *   label = @Translation("Node Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/nodes"
 *   }
 * )
 */
class NodeResource extends ResourceBase
{

    /**
     * The entity type manager service.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * Constructs a new NodeResource object.
     *
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin_id for the plugin instance.
     * @param mixed $plugin_definition
     *   The plugin implementation definition.
     * @param array $serializer_formats
     *   The available serializer formats.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager service.
     * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
     *   The logger channel factory.
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, EntityTypeManagerInterface $entity_type_manager, LoggerChannelFactoryInterface $logger_factory)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger_factory->get('default'));
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->getParameter('serializer.formats'),
            $container->get('entity_type.manager'),
            $container->get('logger.factory')
        );
    }

    /**
     * Responds to GET requests.
     *
     * @return \Drupal\rest\ResourceResponse
     *   The response containing the node data.
     */
    public function get()
    {
        $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple();
        $data = [];

        foreach ($nodes as $node) {
            $data[] = [
                'id' => $node->id(),
                'title' => $node->label(),
                // Add other fields as needed
            ];
        }

        return new ResourceResponse($data);
    }
}
