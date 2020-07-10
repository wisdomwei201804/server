<?php
/**
 * @var array $_
 * @var \OCP\IL10N $l
 * @var \OCP\Authentication\TwoFactorAuth\IProvider $provider
 * @var string $template
 */
$provider = $_['provider'];
$template = $_['template'];
?>
<div class="body-login-container update">
	<h2 class="two-factor-header"><?php p($provider->getDisplayName()); ?></h2>
	<?php print_unescaped($template); ?>
	<p><a class="two-factor-secondary" href="<?php print_unescaped($_['logout_url']); ?>">
			<?php p($l->t('Cancel login')) ?>
	</a></p>
</div>
