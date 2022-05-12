<?php

namespace Drupal\drupalup_block\Plugin\Block;


use Drupal\Core\Block\BlockBase;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\drupalup_block\DateCalculator;

/**
 * Provides a 'Display days block' Block
 * @Block(
 *  id = "drupalup_block",
 *  admin_label = @Translation("Display days block"),
 *  category = @Translation("Display days block category")
 *  )
 */
// implements ContainerFactoryPluginInterface
class DrupalupBlock extends BlockBase implements ContainerFactoryPluginInterface {
    
    protected $dateCalculator;
    
    public function __construct(array $configuration, $plugin_id, $plugin_definition, DateCalculator $dateCalculator){
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->dateCalculator = $dateCalculator;
    }
    
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition){
        return new static (
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('drupalup_block.date_calculator')
        );
    }
    
    /**
     * (@inheritdoc)
     */
    public function build(){
        return [
            '#markup' => $this->displayString(),
            //Disabling cache for this block
            '#cache' => [
            'max-age' => 0
                ],
            ];
    }
    
    /**
     * Private function for returning the correct response for the given $date value
     */
    private function displayString(){
        $node = \Drupal::routeMatch()->getParameter('node');
            if ($node instanceof \Drupal\node\NodeInterface) {
                
                // Get node event date data
                if (!$node->get('field_date')->isEmpty()){
                    $eventdate = $node->get('field_date')->get(0)->get('value')->getValue();
                    $eventdate = substr($eventdate,0,10);
                    $daysreturned = $this->dateCalculator->daysUntilEventStarts($eventdate);
                }
            }
        
        //Text to display for events happening on the current date    
        if($daysreturned == 0){
            return "The event is happening today.";
        }
        //Text to display for events that have ended   
        if($daysreturned < 0){
            return "This event already passed.";
        }
        //Text to display for events happening the next day (day instead of days)   
        if($daysreturned == 1){
            return $daysreturned . " day left until event starts.";
        }
        //Text to display for events happening $daysreturned from the current date
        if($daysreturned > 0){
            return $daysreturned . " days left until event starts.";
        }
        return ;
    }
}
?>