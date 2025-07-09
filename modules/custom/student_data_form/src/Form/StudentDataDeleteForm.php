<?php

namespace Drupal\student_data_form\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;

class StudentDataDeleteForm extends ConfirmFormBase {

  protected $id;

  public function getFormId() {
    return 'student_data_delete_form';
  }

  public function getQuestion() {
    return $this->t('Are you sure you want to delete this student?');
  }

  public function getCancelUrl() {
    return new Url('student_data_form.list');
  }

  public function getConfirmText() {
    return $this->t('Delete');
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    Database::getConnection()->delete('student_data')
      ->condition('id', $this->id)
      ->execute();

    $this->messenger()->addStatus('Student deleted successfully.');
    $form_state->setRedirect('student_data_form.list');
  }
}
