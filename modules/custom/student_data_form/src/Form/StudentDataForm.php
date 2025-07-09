<?php

namespace Drupal\student_data_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StudentDataForm extends FormBase {

  public function getFormId() {
    return 'student_data_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    // Add Bootstrap container and card styling
    $form['#prefix'] = '<div class="container mt-5">
                          <div class="card shadow border-0">
                            <div class="card-body">
                              <h2 class="card-title mb-4">📚 Add Student</h2>';
    $form['#suffix'] = '</div></div></div>';

    // Add Bootstrap form class
    $form['#attributes']['class'][] = 'student-form';

    // Student Name
    $form['student_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Name'),
      '#required' => TRUE,
      '#attributes' => ['class' => ['form-control', 'mb-3']],
    ];

    // Student Class
    $form['student_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Class'),
      '#required' => TRUE,
      '#attributes' => ['class' => ['form-control', 'mb-3']],
    ];

    // Student Section
    $form['student_section'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Section'),
      '#required' => TRUE,
      '#attributes' => ['class' => ['form-control', 'mb-3']],
    ];

    // Student Contact
    $form['student_contact'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Contact Number'),
      '#required' => TRUE,
      '#attributes' => ['class' => ['form-control', 'mb-3']],
    ];

    // Student Address
    $form['student_address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#required' => TRUE,
      '#attributes' => ['class' => ['form-control', 'mb-3']],
    ];

    // Submit Button
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#attributes' => ['class' => ['btn', 'btn-primary']],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $conn->insert('student_data')->fields([
      'student_name' => $form_state->getValue('student_name'),
      'student_class' => $form_state->getValue('student_class'),
      'student_section' => $form_state->getValue('student_section'),
      'student_contact' => $form_state->getValue('student_contact'),
      'student_address' => $form_state->getValue('student_address'),
    ])->execute();

    $this->messenger()->addStatus($this->t('✅ Student data has been saved successfully.'));
  }
}
