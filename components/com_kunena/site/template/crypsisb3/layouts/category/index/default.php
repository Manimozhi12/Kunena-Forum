<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.Category
 *
 * @copyright   (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            http://www.kunena.org
 **/

/** @var KunenaForumCategory $section */
/** @var KunenaForumCategory $category */
/** @var KunenaForumCategory $subcategory */

defined('_JEXEC') or die;

$mmm = 0;

foreach ($this->sections as $section) :
	$markReadUrl = $section->getMarkReadUrl();
	?>
	<div class="kfrontend">
		<div class="btn-toolbar pull-right">
			<?php if (count($this->sections) > 0) : ?>
				<div class="btn btn-default btn-small" data-toggle="collapse" data-target="#section<?php echo $section->id; ?>"></div>
			<?php endif; ?>
		</div>

		<h2 class="btn-link">
			<?php	echo $this->getCategoryLink($section, $this->escape($section->name));	?>
			<small class="hidden-xs nowrap">(<?php echo JText::plural('COM_KUNENA_X_TOPICS',
					$this->formatLargeNumber($section->getTopics())); ?>)
			</small>
		</h2>

		<div class="collapse in section section<?php echo $this->escape($section->class_sfx); ?>" id="section<?php echo $section->id; ?>">
			<table class="table table-bordered">
				<?php if (!empty($section->description)) : ?>
					<thead class="hidden-xs">
					<tr>
						<td colspan="3">
							<div class="header-desc"><?php echo $section->displayField('description'); ?></div>
						</td>
					</tr>
					</thead>
				<?php endif; ?>

				<?php if ($section->isSection() && empty($this->categories[$section->id]) && empty($this->more[$section->id])) : ?>
					<tr>
						<td>
							<h4>
								<?php echo JText::_('COM_KUNENA_GEN_NOFORUMS'); ?>
							</h4>
						</td>
					</tr>
				<?php else : ?>
					<?php if (!empty($this->categories[$section->id])) : ?>
						<td colspan="2" class="hidden-xs">
							<div class="header-desc"><?php echo JText::_('COM_KUNENA_GEN_CATEGORY'); ?></div>
						</td>
						<td colspan="1" class="hidden-xs post-info">
							<?php echo JText::_('COM_KUNENA_GEN_LAST_POST'); ?>
						</td>
					<?php endif; ?>
					<?php
					foreach ($this->categories[$section->id] as $category) : ?>
						<tr class="category<?php echo $this->escape($category->class_sfx); ?>" id="category<?php echo $category->id; ?>">
							<td class="col-md-1 center hidden-xs">
								<?php echo $this->getCategoryLink($category, $this->getCategoryIcon($category), ''); ?>
							</td>
							<td class="col-md-8">
								<div>
									<h3>
										<?php echo $this->getCategoryLink($category); ?>
										<small class="hidden-xs nowrap">
											(<?php echo JText::plural('COM_KUNENA_X_TOPICS', $this->formatLargeNumber($category->getTopics())); ?>)
											<span>
												<?php if (($new = $category->getNewCount()) > 0) : ?>
													<sup class="knewchar"> (<?php echo $new . JText::_('COM_KUNENA_A_GEN_NEWCHAR') ?>)</sup>
												<?php endif; ?>
												<?php if ($category->locked) : ?>
													<span class="glyphicon glyphicon-lock" title="<?php echo JText::_('COM_KUNENA_LOCKED_CATEGORY') ?>"></span>
												<?php endif; ?>
												<?php if ($category->review) : ?>
													<span class="glyphicon glyphicon-shield" title="<?php echo JText::_('COM_KUNENA_GEN_MODERATED') ?>"></span>
												<?php endif; ?>
												<?php if (!empty($category->rssURL)) : ?>
													<a href="<?php echo $category->rssURL ?>" rel="follow">
														 <span class="glyphicon glyphicon-feed" title="<?php echo JText::_('COM_KUNENA_CATEGORIES_LABEL_GETRSS') ?>">
														 </span>
													</a>
												<?php endif; ?>
											</span>
										</small>
									</h3>
								</div>

								<?php if (!empty($category->description)) : ?>
									<div class="hidden-xs header-desc"><?php echo $category->displayField('description'); ?></div>
								<?php endif; ?>

								<?php
								// Display subcategories
								if (!empty($this->categories[$category->id])) : ?>
									<ul class="inline">

										<?php foreach ($this->categories[$category->id] as $subcategory) : ?>
											<li>
												<?php echo $this->getCategoryLink($subcategory, $this->getSmallCategoryIcon($subcategory), '') . $this->getCategoryLink($subcategory) . '<small class="hidden-xs muted"> ('
													. JText::plural('COM_KUNENA_X_TOPICS', $this->formatLargeNumber($subcategory->getTopics()))
													. ')</small>';
												if (($new = $subcategory->getNewCount()) > 0)
												{
													echo '<sup class="knewchar">(' . $new . ' ' . JText::_('COM_KUNENA_A_GEN_NEWCHAR') . ')</sup>';
												}
												?>
											</li>
										<?php endforeach; ?>

										<?php if (!empty($this->more[$category->id])) : ?>
											<li>
												<?php echo $this->getCategoryLink($category, JText::_('COM_KUNENA_SEE_MORE')); ?>
												<small class="hidden-xs muted">
													(<?php echo JText::sprintf('COM_KUNENA_X_HIDDEN', (int) $this->more[$category->id]); ?>)
												</small>
											</li>
										<?php endif; ?>

									</ul>
								<?php endif; ?>

								<?php if (!empty($category->moderators)) : ?>
									<div class="kthead-moderators ks">
										<?php
										// get the Moderator list for display
										$modslist = array();
										foreach ($category->moderators as $moderator)
										{
											$modslist[] = KunenaFactory::getUser($moderator)->getLink(null, null, 'nofollow', '', null, $category->id);
										}
										echo JText::_('COM_KUNENA_MODERATORS') . ': ' . implode(', ', $modslist);
										?>
									</div>
								<?php endif; ?>

								<?php if (!empty($this->pending[$category->id])) : ?>
									<div class="alert alert-warning" role="alert" style="margin-top:20px;">
										<a class="alert-link" href="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=topics&layout=posts&mode=unapproved&userid=0&catid=' . intval($category->id)); ?>" title="<?php echo JText::_('COM_KUNENA_SHOWCAT_PENDING')?>" rel="nofollow"><?php echo intval($this->pending[$category->id]) . ' ' . JText::_('COM_KUNENA_SHOWCAT_PENDING')?></a>
									</div>
								<?php endif; ?>
							</td>

							<?php $last = $category->getLastTopic(); ?>

							<?php if ($last->exists()) :
								$author = $last->getLastPostAuthor();
								$time   = $last->getLastPostTime();
								$avatar = $this->config->avataroncat ? $author->getAvatarImage('img-rounded', 'post') : null;
							?>

								<td class="col-md-3 hidden-xs">
									<div class="container-fluid">
										<div class="col-md-12">
											<?php if ($avatar) : ?>
												<div class="col-md-3">
													<?php echo $author->getLink($avatar); ?>
												</div>
											<?php endif; ?>
											<div class="col-md-9">
												<span><?php echo $this->getLastPostLink($category) ?></span>
												<br>
												<span><?php echo JText::sprintf('COM_KUNENA_BY_X', $author->getLink()); ?></span>
												<br>
												<span><?php echo $time->toKunena('config_post_dateformat'); ?></span>
											</div>
										</div>
									</div>
								</td>
							<?php else : ?>
								<td class="col-md-3 hidden-xs">
									<div class="last-post-message">
										<?php echo JText::_('COM_KUNENA_X_TOPICS_0'); ?>
									</div>
								</td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if (!empty($this->more[$section->id])) : ?>
					<tr>
						<td colspan="3">
							<h4>
								<?php echo $this->getCategoryLink($section, JText::sprintf('COM_KUNENA_SEE_ALL_SUBJECTS')); ?>
								<small>(<?php echo JText::sprintf('COM_KUNENA_X_HIDDEN', (int) $this->more[$section->id]); ?>)</small>
							</h4>
						</td>
					</tr>
				<?php endif; ?>

			</table>
		</div>
	</div>
	<!-- Begin: Category Module Position -->
	<?php echo $this->subLayout('Widget/Module')->set('position', 'kunena_section_' . ++$mmm); ?>
	<!-- Finish: Category Module Position -->
<?php endforeach; ?>
