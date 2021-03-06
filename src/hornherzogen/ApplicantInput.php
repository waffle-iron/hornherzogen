<?php
namespace hornherzogen;

/**
 * Class ApplicantInput
 * holds any validation errors when processing user data extracted from $_POST[].
 * @package hornherzogen
 */
class ApplicantInput extends Applicant
{
    private $errors = array();
    private $success = array();

    /**
     * @var int total elements that can be provided in the web form.
     */
    private $total = 17;

    public function hasErrors()
    {
        return !($this->total == sizeof($this->success) && 0 == sizeof($this->errors));
    }

    public function showHasError($field) {
        // TODO if field is in $errors
        return " has-error has-feedback";
    }

    public function showIsOkay($field) {
        // TODO if field is in $success
        return " has-success";
    }

    /**
     * Extracts data from $_POST[].
     */
    public function parse() {

    }

}