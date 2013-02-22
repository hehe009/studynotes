<?php
/**
 * BJOU studynotes block
 * @package   bjou_studynotes
 * @copyright Copyright Agency Limited
 * @author    Max Kan <max@pukunui.com>, Pukunui Technology
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_bjoustudynotes extends block_base {
    public function init() {
        $this->title = get_string('title', 'block_bjoustudynotes');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our SimpleHTML block!';
        $this->content->footer = 'Footer here...';

        return $this->content;
    }
}