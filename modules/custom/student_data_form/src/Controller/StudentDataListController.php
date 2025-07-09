<?php

namespace Drupal\student_data_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

class StudentDataListController extends ControllerBase {

  public function studentList() {
    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'class' => $this->t('Class'),
      'section' => $this->t('Section'),
      'contact' => $this->t('Contact'),
      'address' => $this->t('Address'),
      'operations' => $this->t('Actions'),
    ];

    $rows = [];
    $conn = Database::getConnection();
    $results = $conn->select('student_data', 's')
      ->fields('s', ['id', 'student_name', 'student_class', 'student_section', 'student_contact', 'student_address'])
      ->execute();

    foreach ($results as $record) {
      $edit_url = Url::fromRoute('student_data_form.edit', ['id' => $record->id]);
      $delete_url = Url::fromRoute('student_data_form.delete', ['id' => $record->id]);

      $edit_link = Link::fromTextAndUrl('✏️ Edit', $edit_url)->toRenderable();
      $delete_link = Link::fromTextAndUrl('🗑️ Delete', $delete_url)->toRenderable();

      foreach ([$edit_link, $delete_link] as &$link) {
        $link['#attributes']['class'] = ['btn', 'btn-sm'];
        $link['#options']['html'] = TRUE;
      }
      $edit_link['#attributes']['class'][] = 'btn-outline-primary';
      $delete_link['#attributes']['class'][] = 'btn-outline-danger';

      $rows[] = [
        'id' => [
          'data' => ['#markup' => '<strong>' . $record->id . '</strong>'],
        ],
        'name' => [
          'data' => ['#markup' => '<strong>' . $record->student_name . '</strong>'],
        ],
        'class' => [
          'data' => ['#markup' => '<span class="badge bg-info text-dark">' . $record->student_class . '</span>'],
        ],
        'section' => [
          'data' => ['#markup' => '<span class="badge bg-secondary">' . $record->student_section . '</span>'],
        ],
        'contact' => [
          'data' => ['#markup' => '<code>' . $record->student_contact . '</code>'],
        ],
        'address' => [
          'data' => ['#markup' => $record->student_address],
        ],
        'operations' => [
          'data' => [
            '#type' => 'container',
            '#attributes' => ['class' => ['d-flex', 'gap-2']],
            'edit' => $edit_link,
            'delete' => $delete_link,
          ],
        ],
      ];
    }

    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['container', 'mt-5']],
      'title' => [
        '#markup' => '<h2 class="mb-4">📋 Student List</h2>',
      ],
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['table', 'table-striped', 'table-bordered', 'table-hover'],
        ],
        '#empty' => $this->t('No student records found.'),
      ],
    ];
  }
}
