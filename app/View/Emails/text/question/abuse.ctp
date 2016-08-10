<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.text
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
Dear Admin,

Abuse/Wrong Category reported for this question titled as "<?php echo $question['title']; ?>".

Commented as follows:

"<?php echo $comment; ?>"


To view this question follow this URL:  
<?php echo $loginUrl . 'questions/view/' . md5($question['id']); ?>

Reported by <?php echo AuthComponent::user('name') . ' (' . AuthComponent::user('email') . ')' ?>

To view profile of this user follow this URL:  
<?php echo $loginUrl . 'backoffice/users/edit/' . md5(AuthComponent::user('id')); ?>

