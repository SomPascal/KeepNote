<?php
/**
 * @var App\Entities\User $user The user
 */

use App\Cells\GreetingCell;

echo $this->include("parts/head");
echo $this->include("parts/navbar");
?>

<main class="main">
    <section class="notes-container center-2">
		<div class="notes-box">
			<ul>
				<p class="h3 opacity-7" no-notes>
					<?= esc(lang('Button.pending-notes')) ?>
				</p>
			</ul>
		</div>

		<div class="notes-assets">
			<?= view_cell(sprintf("%s::greet", GreetingCell::class), ["username" => $user->username()]) ?>

			<p class="tag is-warning is-light is-bold opacity-7">
				<!-- x note(s) saved -->
			</p>

			<div class="notes-assets-buttons">
				<div class="buttons">
					<button class="button is-bold is-info is-small is-rounded" id="select-all-notes" disabled="disabled">
						<?= esc(lang('Button.select-all')) ?>
						<i class="fa fa-check-circle"></i>
					</button>

					<button class="button is-bold is-info  is-small is-rounded" id="unselect-all-notes" disabled="disabled">
						<?= esc(lang('Button.unselect-all')) ?>
						<i class="fa fa-times-circle"></i>
					</button>
				</div>

				<div class="buttons">
					<button type="button" class="button is-warning is-light is-bold is-rounded" multiple-notes-feat="false" id="extend-btn" disabled="disabled">
						<span>
							<?= esc(lang('Button.extend')) ?>
						</span>
						<i class="fa fa-arrows"></i>
					</button>

					<button type="button" class="button is-warning is-light is-bold is-rounded" multiple-notes-feat="true" id="copy-btn" disabled="disabled">
						<span>
							<?= esc(lang("Button.copy")) ?>
						</span>
						<i class="fa fa-copy"></i>
					</button>

					<button type="button" class="button is-warning is-light is-bold is-rounded" multiple-notes-feat="false" id="edit-btn" disabled="disabled">
						<span>
							<?= esc(lang('Button.edit')) ?>
						</span>
						<i class="fa fa-edit"></i>
					</button>

					<button type="button" class="button is-warning is-light is-bold is-rounded" multiple-notes-feat="true" id="share-btn" disabled="disabled">
						<span>
							<?= esc(lang('Button.share')) ?>
						</span>
						<i class="fa fa-share-alt"></i>
					</button>

					<button type="button" class="button is-warning is-light is-bold is-rounded" multiple-notes-feat="true" id="download-btn" disabled="disabled">
						<span>
							<?= esc(lang('Button.download')) ?>
						</span>
						<i class="fa fa-download"></i>
					</button>

					<button type="button" class="button is-danger is-light is-bold is-rounded" multiple-notes-feat="true" id="delete-btn" disabled="disabled">
						<span>
							<?= esc(lang('Button.delete')) ?>
						</span>
						<i class="fa fa-trash"></i>
					</button>
				</div>
			</div>
		</div>
    </section>
</main>

<section class="windows-box">
	<div class="extended-note trans-1 hide">
		<form method="UPDATE" action="<?= esc(url_to("note.update"), "attr") ?>" >
			<div class="navigation">
				<button id="exit-extended-note" type="button" class="button is-bold is-black is-light is-rounded">
					<?= esc(lang('Button.exit')) ?> 
					<i class="fa fa-times-circle"></i>
				</button>

				<div class="buttons trans-1 hide" id="notes_aesthetics">
					<button type="button" class="button is-black is-bold is-light is-rounded">
						<i class="fa fa-paint-brush"></i>
					</button>
					
					<button type="button" class="button is-black is-bold is-light is-rounded">
						<i class="fa fa-font"></i>
					</button>
				</div>

				<button id="create-new-note" type="submit" class="button is-bold hide is-info is-rounded">
					<?= esc(lang('Button.create')) ?>
				</button>

				<button type="button" id="edit-extended-note" class="button is-info is-bold is-rounded">
					<?= esc(lang('Button.edit')) ?> 
					<i class="fa fa-pencil-square-o"></i>
				</button>

				<div class="buttons trans-1 hide" id="update-note-btns">
					<button type="button" class="button is-danger is-light is-bold is-rounded">
						<?= esc(lang('Button.cancel')) ?>
					</button>
					
					<button type="submit" class="button is-info is-bold is-rounded">
						<?= esc(lang('Button.save')) ?>
					</button>
				</div>
			</div>

			<div class="notes-data">
				<div class="field">
					<div class="notification is-danger is-light hide">
						<p></p>
						<button class="delete" type="button"></button>
					</div>
				</div>

				<div class="field">
					<div class="control is-medium">
						<input type="text" style="font-family: poppins;" class="input is-warning is-rounded is-fullwidth" 
						placeholder="<?= esc(lang('Placeholder.note.title'), "attr") ?>" maxlength="300" id="note_title" readonly="readonly"/>
					</div>
				</div>

				<div class="field">
					<div class="control is-medium">
						<textarea placeholder="<?= esc(lang('Placeholder.note.body'), "attr") ?>" style="font-family: poppins;" class="textarea is-warning is-rounded is-fullwidth" maxlength="800" id="note_body" readonly="readonly" required="required"></textarea>
					</div>
				</div>

				<input type="hidden" id="extended_note_font" value="poppins" required />
				<input type="hidden" id="extended_note_color" value="#f2f2f27a" required />
			</div>
		</form>
	</div>

	<div class="box window trans-1 hide" id="download-notes">
		<?= $forms->download->open() ?>
			<h2><?= esc(lang('Header.title.download-notes')) ?></h2>
			
			<div class="notification is-danger is-light is-bold hide">
			    <button class="delete is-light" type="button"></button>
				<p>
					<i class="fa fa-warning"></i>
					
					<?= lang('Label.download-limit') ?>
				</p>
			</div>

			<div class="field is-bold">
				<div class="tag is-success is-light is-medium">
					<?= esc(lang('Label.selected')) ?> : <span>0</span>
				</div>
			</div>

			<div class="field">
				<p><?= esc(lang('Header.body.download-notes')) ?></p>
			</div>

			<div class="field trans-1 is-flex is-flex-direction-row is-flex-wrap-wrap is-justify-content-space-around">
				<?php foreach ($forms->download->radios() as $radio): ?>
					<?= $radio ?>
				<?php endforeach ?>
			</div>

			<div class="field">
				<div class="buttons">
					<?= $forms->download->submit("submit") ?>
					<?= $forms->download->button("cancel") ?>
				</div>
			</div>
		<?= $forms->download->close() ?>
	</div>

	<div class="box window trans-1 hide" id="upload-notes">
		<form action="<?= esc(route_to("note.active.download"), "attr") ?>" enctype="multipart/form-data">
			<div class="field">
				<div class="file is-info is-light has-name is-boxed is-small is-fullwidth">
					<label class="file-label">
						<input type="file" id="downloaded-notes" class="file-input" size="10" multiple="true" required="required">
						<span class="file-cta">
							<span class="file-icon">
								<i class="fa fa-cloud-upload"></i>
							</span>
							<span class="file-label">
								<?= esc(lang("Label.tap-to-upload")) ?>
							</span>
						</span>
						<span class="file-name">
							<?= lang("Label.no-notes-downloaded") ?>
						</span>
					</label>
				</div>
			</div>

			<div class="field hide">
				<span class="help">
					<!-- An error occured. The valid extensions are: xml and json -->
				</span>
			</div>

			<div class="field hide">
				<progress max="100" min="0" step="1" value="100" class="progress is-small is-success"></progress>
				<span class="help is-success is-bold">Completed: 100%</span>
			</div>

			<div class="buttons">
				<button type="submit" id="submit-upload" class="button is-warning is-bold is-rounded is-fullwidth">
					<?= esc(lang('Button.import')) ?>
				</button>

				<button type="button" id="cancel-upload" class="button is-warning is-bold is-light is-rounded is-fullwidth">
					<?= esc(lang('Button.cancel')) ?> 
					<i class="fa fa-times-circle"></i>
				</button>
			</div>
		</form>
	</div>

	<div class="box window trans-1 hide" id="share-notes">
		<form action="<?= esc(route_to("note.share.link"), "attr") ?>">
			<h2><?= esc(lang('Header.title.share-notes')) ?></h2>

			<div class="field">
				<div class="notification is-danger is-light is-small is-bold hide">
					<button class="delete" type="button"></button>
					<p></p>
				</div>
			</div>

			<div class="field">
				<div class="tag is-info">
					<?= esc(lang('Label.selected')) ?>: <span></span>
				</div>
			</div>

			<div class="field">
				<div class="control">
					<input type="link" class="input is-warning is-rounded" placeholder="<?= esc(lang('Placeholder.share-link'), "attr") ?>" readonly="readonly">
				</div>
			</div>

			<div class="buttons">
				<button type="button" class="button is-warning is-rounded is-bold is-fullwidth is-hidden" id="copy-share-link">
					<?= esc(lang('Button.copy')) ?>
					<i class="fa fa-copy"></i>
				</button>

				<button type="button" class="button is-warning is-rounded is-bold is-fullwidth is-hidden" id="save-share-link">
					<?= esc(lang('Button.save-as-note')) ?>
					<i class="fa fa-save"></i>
				</button>

				<button type="submit" class="button is-warning is-rounded is-bold is-fullwidth" id="submit-share" disabled="disabled">
					<?= esc(lang('Button.gen-link')) ?>
					<i class="fa fa-link"></i>
				</button>

				<button type="button" class="button is-warning is-light is-rounded is-bold is-fullwidth" id="cancel-share">
					<?= esc(lang('Button.cancel')) ?>
					<i class="fa fa-times-circle"></i>
				</button>
			</div>
		</form>
	</div>

	<div class="box window trans-1 hide" id="delete-notes">
		<form method="POST" action="<?= esc(route_to("note.delete"), "attr") ?>">
			<h2>
				<?= esc(lang('Header.title.delete-notes')) ?>
			</h2>

			<div class="field notification is-danger is-light hide">
				<button type="button" class="delete"></button>
				<p>
					<?= esc(lang('Error.default')) ?>
				</p>
			</div>

			<div class="field">
				<div class="tag is-normal is-info">
					<?= esc(lang('Label.selected')) ?>: <span></span>
				</div>
				<p>
					<!-- Are you sure to really delete these.is (6) note(s) ? -->
				</p>
			</div>

			<div class="field">
				<div class="buttons">
					<button type="submit" class="button is-danger is-rounded is-fullwidth is-bold" id="submit-delete" disabled="disabled">
						<?= esc(lang('Button.delete')) ?>
						<i class="fa fa-trash"></i>
					</button>

					<button type="button" class="button is-danger is-light is-rounded is-fullwidth is-bold" id="cancel-delete">
						<?= esc(lang('Button.cancel')) ?> 
						<i class="fa fa-times-circle"></i>
					</button>
				</div>
			</div>
		</form>
	</div>
</section>

<section class="create-notes-groupe-btn trans-1">
	<div class="add-notes-btn trans-1" id="create_text_note" title="Create note">
		<i class="fa-solid fa-keyboard"></i>
	</div>

	<div id="upload_downloaded_notes" class="add-notes-btn trans-1" title="Upload note">
		<i class="fa fa-cloud-upload"></i>
	</div>

	<div class="add-notes-btn" id="create_note" is-active="false">
		<i class="fa fa-plus"></i>
	</div>
</section>

<section class="notification bottom-window-msg hide" id="notification">
	<button class="delete"></button>
    <p class="is-size-5"></p>
</section>

<script src="/assets/libs/validate.js/validate.min.js"></script>
<?= $this->include("parts/footer.php") ?>