<?php

enum FormName: string {
  case Login = 'login';
  case Register = 'register';
}

/**
 * Generates an HTML form
 * @param FormName $formName
 * @return void
 */
function generate_form(FormName $formName) {
  switch($formName) {
    case FormName::Login:
    case FormName::Register:
      ?>
      <form method="post" class="form-horizontal">
        <?php if ($formName === FormName::Register): ?>
          <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Name:</label>
            <div class="col-sm-4">
              <input type="text" name="name" id="name-input" class="form-control">
            </div>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">Email:</label>
          <div class="col-sm-4">
            <input type="text" name="email" id="email-input" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">Password:</label>
          <div class="col-sm-4">
            <input type="password" name="pass" id="password-input" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <input
              type="submit"
              name="<? $formName === FormName::Login ? 'login' : 'register' ?>"
              value="<? $formName === FormName::Login ? 'Log In' : 'Register' ?>"
              class="btn btn-primary"
            >
            <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
          </div>
        </div>
      </form>
      <?php
      break;
  }
}
