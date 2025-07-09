<?php

namespace Drupal\student_data_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StudentDataEditForm extends FormBase {

  public function getFormId() {
    return 'student_data_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $conn = Database::getConnection();
    $record = $conn->select('student_data', 's')
      ->fields('s')
      ->condition('id', $id)
      ->execute()
      ->fetchObject();

    $form['id'] = ['#type' => 'hidden', '#value' => $id];
    $form['student_name'] = ['#type' => 'textfield', '#title' => 'Student Name', '#default_value' => $record->student_name, '#required' => TRUE];
    $form['student_class'] = ['#type' => 'textfield', '#title' => 'Student Class', '#default_value' => $record->student_class, '#required' => TRUE];
    $form['student_section'] = ['#type' => 'textfield', '#title' => 'Student Section', '#default_value' => $record->student_section, '#required' => TRUE];
    $form['student_contact'] = ['#type' => 'textfield', '#title' => 'Contact Number', '#default_value' => $record->student_contact, '#required' => TRUE];
    $form['student_address'] = ['#type' => 'textarea', '#title' => 'Address', '#default_value' => $record->student_address, '#required' => TRUE];

    $form['submit'] = ['#type' => 'submit', '#value' => 'Update'];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    Database::getConnection()->update('student_data')
      ->fields([
        'student_name' => $form_state->getValue('student_name'),
        'student_class' => $form_state->getValue('student_class'),
        'student_section' => $form_state->getValue('student_section'),
        'student_contact' => $form_state->getValue('student_contact'),
        'student_address' => $form_state->getValue('student_address'),
      ])
      ->condition('id', $form_state->getValue('id'))
      ->execute();

    $this->messenger()->addStatus('Student updated successfully.');
    $form_state->setRedirect('student_data_form.list');
  }
}
