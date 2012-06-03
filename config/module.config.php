<?php
return array(
    'view_manager' => array(
        'helper_map' => array(
            'slug' => 'SlmCmfUtils\View\Helper\Slug',
        ),
    ),

    'controller' => array(
        'map' => array(
            'slug' => 'SlmCmfUtils\Controller\Plugin\Slug',
        ),
    ),
    
    'di' => array(
        'instance' => array(
            'SlmCmfUtils\Filter\Slug' => array(
                'parameters' => array(
                    'slugifier' => 'Bacon\Text\Slugifier\Slugifier'
                ),
            ),
            'SlmCmfUtils\Controller\Plugin\Slug' => array(
                'parameters' => array(
                    'filter' => 'SlmCmfUtils\Filter\Slug'
                ),
            ),
            'SlmCmfUtils\View\Helper\Slug' => array(
                'parameters' => array(
                    'filter' => 'SlmCmfUtils\Filter\Slug'
                ),
            ),
        ),
    ),
);
