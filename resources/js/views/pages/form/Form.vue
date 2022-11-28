<template>
<div class="application-form">
  <notifications classes="notification" />
  <site-header>
    <icon-logo />
  </site-header>
  <site-main>
    <site-wrapper>
      <site-grid>
        <template v-if="isSuccess">
          <div class="application-form__success">
            <h1>Vielen Dank</h1>
            <p>Wir haben Ihre Daten erhalten und werden diese in der kommenden Zeit prüfen.</p>
          </div>
        </template>
        <template v-else>

          <template>
            <h2 class="span">Institution</h2>
            <div class="span">
              <application-row :class="[errors.name ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.name ? errors.name : 'Name der Institution *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.name" required :class="[errors.name ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('name')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.legal_form ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.legal_form ? errors.legal_form : 'Rechtsform *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.legal_form" required :class="[errors.legal_form ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('legal_form')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row class="application-row__form">
                <application-label :cls="'span-4 sm:span-5'">Website</application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.website">
                </application-input>
              </application-row>
            </div>

          </template>

          <template>
            <h2 class="span">Kontaktperson</h2>
            <div class="span">
              <application-row :class="[errors.firstname ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.firstname ? errors.firstname : 'Vorname *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.firstname" required :class="[errors.firstname ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('firstname')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.lastname ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.lastname ? errors.lastname : 'Name *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.lastname" required :class="[errors.lastname ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('lastname')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.phone ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.phone ? errors.phone : 'Telefon *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.phone" required :class="[errors.phone ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('phone')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.email ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.email ? errors.email : 'E-Mail *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="email" v-model="form.email" required :class="[errors.email ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('email')">
                </application-input>
              </application-row>
            </div>
          </template>

          <template>
            <h2 class="span">Kontoangaben</h2>
            <div class="span">
              <application-row :class="[errors.iban ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.iban ? errors.iban : 'IBAN *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.iban" required :class="[errors.iban ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('iban')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.iban ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.beneficiary ? errors.beneficiary : 'Begünstigter *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.beneficiary" required :class="[errors.beneficiary ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('beneficiary')">
                </application-input>
              </application-row>
            </div>
          </template>

          <template>
            <h2 class="span">Adresse</h2>
            <div class="span">
              <application-row :class="[errors.street ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.street ? errors.street : 'Strasse *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.street" required :class="[errors.street ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('street')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row class="application-row__form">
                <application-label :cls="'span-4 sm:span-5'">
                  Nummer
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.street_number">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.zip ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.zip ? errors.zip : 'PLZ *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.zip" required :class="[errors.zip ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('zip')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.city ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.city ? errors.city : 'Ort *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.city" required :class="[errors.city ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('city')">
                </application-input>
              </application-row>
            </div>
          </template>



          <template>
            <h2 class="span">Dokumente</h2>
            <div class="span">
              <application-row :class="[errors.file_portrait ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_portrait ? errors.file_portrait : 'Kurzportrait (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.portrait">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.portrait.name}`" class="anchor-download" target="_blank">
                        {{form.files.portrait.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.portrait.name, 'portrait')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'portrait', 'Portrait')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.file_annual_report ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_annual_report ? errors.file_annual_report : 'Jahresbericht (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.annual_report">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.annual_report.name}`" class="anchor-download" target="_blank">
                        {{form.files.annual_report.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.annual_report.name, 'annual_report')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'annual_report', 'Jahresbericht')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.file_annual_financial_report? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_annual_financial_report ? errors.file_annual_financial_report : 'Jahresrechnung (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.annual_financial_report">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.annual_financial_report.name}`" class="anchor-download" target="_blank">
                        {{form.files.annual_financial_report.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.annual_financial_report.name, 'annual_financial_report')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'annual_financial_report', 'Jahresrechnung')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.file_budget ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_budget ? errors.file_budget : 'Budget (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.budget">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.budget.name}`" class="anchor-download" target="_blank">
                        {{form.files.budget.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.budget.name, 'budget')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'budget', 'Budget')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.file_bylaws ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_bylaws ? errors.file_bylaws : 'Statuten (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.bylaws">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.bylaws.name}`" class="anchor-download" target="_blank">
                        {{form.files.bylaws.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.bylaws.name, 'bylaws')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'bylaws', 'Statuten')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
          </template>

          <template>
            <h2 class="span">Projekt</h2>
            <div class="span">
              <application-row :class="[errors.project_title ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_title ? errors.project_title : 'Projekttitel *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="text" v-model="form.project_title" required :class="[errors.project_title ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_title')">
                </application-input>
              </application-row>
            </div>
            <div class="span mb-6x">
              <application-row :class="[errors.justification_funds ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.justification_funds ? errors.justification_funds : 'Kurzbegründung Mittelbedarf *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <textarea v-model="form.justification_funds" maxlength="500" required :class="[errors.justification_funds ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('justification_funds')"></textarea>
                  <text-length-indicator :field="form.justification_funds" />
                </application-input>
              </application-row>
            </div>
            <div class="span mb-6x">
              <application-row :class="[errors.project_beneficiaries ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_beneficiaries ? errors.project_beneficiaries : 'Begünstigte des Projekts *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <textarea v-model="form.project_beneficiaries" maxlength="500" required :class="[errors.project_beneficiaries ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_beneficiaries')"></textarea>
                  <text-length-indicator :field="form.project_beneficiaries" />
                </application-input>
              </application-row>
            </div>
            <div class="span mb-6x">
              <application-row :class="[errors.proportion_residents_benefit_program ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.proportion_residents_benefit_program ? errors.proportion_residents_benefit_program : 'Anteil begünstigte Stadtzürcherinnen und Stadtzürcher (falls quantifizierbar) *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <textarea v-model="form.proportion_residents_benefit_program" maxlength="500" required :class="[errors.proportion_residents_benefit_program ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('proportion_residents_benefit_program')"></textarea>
                  <text-length-indicator :field="form.proportion_residents_benefit_program" />
                </application-input>
              </application-row>
            </div>
            <div class="span mb-6x">
              <application-row :class="[errors.project_time ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_time ? errors.project_time : 'Projektdauer/Zeitraum *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <textarea v-model="form.project_time" maxlength="500" required :class="[errors.project_time ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_time')"></textarea>
                  <text-length-indicator :field="form.project_time" />
                </application-input>
              </application-row>
            </div>
            <div class="span mb-3x">
              <application-row :class="[errors.file_project_description ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_project_description ? errors.file_project_description : 'Beschreibung und Begründung des Projekts (PDF, max. fünf Seiten) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.project_description">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.project_description.name}`" class="anchor-download" target="_blank">
                        {{form.files.project_description.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.project_description.name, 'project_description')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'project_description', 'Projekt')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.file_project_estimated_costs ? 'has-error' : '', 'application-row__form']">
                <div class="span-4 sm:span-5">
                  <label>
                    {{ errors.file_project_estimated_costs ? errors.file_project_estimated_costs : 'Detaillierter Kostenvoranschlag, eventuell Richtpreisofferten (PDF) *' }}
                  </label>
                </div>
                <div class="span-8 sm:span-7">
                  <template v-if="form.files.project_estimated_costs">
                    <div class="flex justify-between">
                      <a :href="`/storage/uploads/temp/${form.files.project_estimated_costs.name}`" class="anchor-download" target="_blank">
                        {{form.files.project_estimated_costs.name | truncate(80, '...')}}
                      </a>
                      <a 
                        href="javascript:;" 
                        @click="uploadDelete(form.files.project_estimated_costs.name, 'project_estimated_costs')" 
                        class="icon-trash ml-3x">
                        <icon-trash />
                      </a>
                    </div>
                  </template>
                  <template v-else>
                    <vue-dropzone
                      ref="dropzone"
                      id="dropzone"
                      :options="config"
                      @vdropzone-sending="uploadSending"
                      @vdropzone-success="uploadSuccess($event, 'project_estimated_costs', 'KV')"
                      @vdropzone-complete="uploadComplete"
                      @vdropzone-max-files-exceeded="uploadMaxFilesExceeded"
                      :useCustomSlot=true
                    >
                      <button class="btn-upload">Datei auswählen</button>
                    </vue-dropzone>
                  </template>
                </div>
              </application-row>
            </div>
          </template>

          <template>
            <h2 class="span">Projektkosten und -finanzierung</h2>
            <div class="span">
              <application-row :class="[errors.project_cost_total ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_cost_total ? errors.project_cost_total : 'Projektkosten Total (CHF) *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" v-model="form.project_cost_total" required :class="[errors.project_cost_total ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_cost_total')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.project_own_contribution ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_own_contribution ? errors.project_own_contribution : 'Eigenleistungen Total (CHF) *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" v-model="form.project_own_contribution" required :class="[errors.project_own_contribution ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_own_contribution')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.project_contribution_requested ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_contribution_requested ? errors.project_contribution_requested : 'Beantragter Beitrag Dr. Stephan à Porta-Stiftung (CHF) *' }}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" v-model="form.project_contribution_requested" required :class="[errors.project_contribution_requested ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_contribution_requested')">
                </application-input>
              </application-row>
            </div>
            <div class="span">
              <application-row :class="[errors.project_contribution_further_requested ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_contribution_further_requested ? errors.project_contribution_further_requested : 'Weitere beantragte Beiträge (CHF) *'}}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" v-model="form.project_contribution_further_requested" required :class="[errors.project_contribution_further_requested ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_contribution_further_requested')">
                </application-input>
              </application-row>
            </div>

            <div class="span" v-if="(form.project_cost_total > 0) && (openAmount > 0)">
              <application-row class="application-row__form">
                <application-label :cls="'span-4 sm:span-5'">
                  Offener Betrag (CHF)
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" readonly :value="openAmount" >
                </application-input>
              </application-row>
            </div>

            <div class="span">
              <application-row class="application-row__form" v-if="openAmount > 0">
                <application-label :cls="'span-4 sm:span-5'">
                  Wie wird der offene Betrag finanziert?
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <textarea v-model="form.project_finance" maxlength="500"></textarea>
                  <text-length-indicator :field="form.project_finance" />
                </application-input>
              </application-row>
            </div>


          </template>

          <template>
            <h2 class="span">Beiträge Dritter</h2>
            <p class="span">Sofern Sie für das gleiche Gesuch weitere Institutionen um einem Beitrag ersuchen, bitten wir um folgende Angaben:</p>
            <template v-if="errors.project_add_instit_2 || errors.project_add_instit_total_2 || errors.project_add_instit_3 || errors.project_add_instit_total_3 || errors.project_add_instit_4 || errors.project_add_instit_total_4 || errors.project_add_instit_5 || errors.project_add_instit_total_5">
              <p class="has-error">Es sind nicht alle Felder korrekt ausgefüllt:</p>
            </template>
            <div class="span">
              <application-row class="application-row__form">
                <application-input :cls="'span-4 sm:span-4'">
                  <label class="mb-2x">Institution</label>
                  <input 
                    class="mb-2x"
                    type="text" 
                    v-model="form[`project_add_instit_${add}`]" 
                    :class="[errors[`project_add_instit_${add}`] ? 'is-invalid' : '', ''] " @focus="removeError([`project_add_instit_${add}`])"
                    v-for="(add, index) in [2, 3, 4, 5]" :key="index">
                </application-input>
                <application-input :cls="'span-4 sm:span-4'">
                  <label class="mb-2x">Betrag</label>
                  <input 
                    class="mb-2x"
                    type="number" 
                    min="0" 
                    v-model="form[`project_add_instit_total_${add}`]" 
                    :class="[errors[`project_add_instit_total_${add}`] ? 'is-invalid' : '', ''] " @focus="removeError([`project_add_instit_total_${add}`])"
                    v-for="(add, index) in [2, 3, 4, 5]" :key="index">
                </application-input>
                <application-input :cls="'span-4 sm:span-4'">
                  <label class="mb-2x">Antwort</label>
                  <div class="select-wrapper mb-2x" v-for="(add, index) in [2, 3, 4, 5]" :key="index">
                    <select v-model="form[`project_add_instit_answer_${add}`]">
                      <option :value="'Zusage'">Zusage</option>
                      <option :value="'Absage'">Absage</option>
                      <option :value="'offen'">offen</option>
                    </select>
                  </div>
                </application-input>
              </application-row>


            </div>
          </template>

          <template>
            <h2 class="span">Budgetierter jährlicher Ertrag aus dem Projekt</h2>
            <div class="span">
              <application-row :class="[errors.project_income ? 'has-error' : '', 'application-row__form']">
                <application-label :cls="'span-4 sm:span-5'">
                  {{ errors.project_income ? errors.project_income : 'Budgetierter jährlicher Ertrag in CHF *'}}
                </application-label>
                <application-input :cls="'span-8 sm:span-7'">
                  <input type="number" min="0" v-model="form.project_income" required :class="[errors.project_income ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_income')">
                </application-input>
              </application-row>
            </div>
          </template>

          <template>
            <div class="span mt-12x">
              <a 
                href="javascript:;" 
                :class="[isSaving ? 'disabled' : '', 'btn-submit is-small']"
                @click.prevent="submit()">Anfrage senden</a>
            </div>
          </template>

        </template>
      </site-grid>
    </site-wrapper>
  </site-main>
  <dialog-wrapper ref="dialogDestroy">
    <template #message>
      <div><strong>Möchten Sie diese Datei wirklich löschen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="deleteUpload()">Ja, löschen</a>
    </template>
  </dialog-wrapper>
</div>
</template>

<script>
import NProgress from 'nprogress';
import ErrorHandling from '@/mixins/ErrorHandling';
import SiteMain from '@/views/pages/form/components/Main.vue';
import SiteHeader from '@/views/pages/form/components/Header.vue';
import SiteWrapper from '@/components/layout/Wrapper.vue';
import SiteGrid from '@/views/pages/form/components/Grid.vue';
import ApplicationRow from '@/components/layout/Row.vue';
import ApplicationLabel from '@/components/layout/Label.vue';
import ApplicationInput from '@/components/layout/Input.vue';
import vue2Dropzone from 'vue2-dropzone';
import DialogWrapper from '@/components/ui/misc/Dialog.vue';
import IconTrash from '@/components/ui/icons/Trash-sm.vue';
import IconLogo from '@/components/ui/icons/Logo.vue';
import TextLengthIndicator from '@/views/pages/form/components/TextLengthIndicator.vue';

export default {

  components: {
    SiteMain,
    SiteHeader,
    SiteWrapper,
    SiteGrid,
    ApplicationRow,
    ApplicationLabel,
    ApplicationInput,
    DialogWrapper,
    vueDropzone: vue2Dropzone,
    IconTrash,
    IconLogo,
    TextLengthIndicator
  },

  mixins: [ErrorHandling],

  data() {
    return {
      
      form: {
        files: {
          portrait: null,
          annual_report: null,
          annual_financial_report: null,
          budget: null,
          bylaws: null,
          project_description: null,
          project_estimated_costs: null,
        },
        project_add_instit_2: null,
        project_add_instit_3: null,
        project_add_instit_4: null,
        project_add_instit_5: null,
        project_add_instit_total_2: null,
        project_add_instit_total_3: null,
        project_add_instit_total_4: null,
        project_add_instit_total_5: null,
        project_add_instit_answer_2: 'offen',
        project_add_instit_answer_3: 'offen',
        project_add_instit_answer_4: 'offen',
        project_add_instit_answer_5: 'offen',
      },

      errors: {
        name: false,
        legal_form: false,
        bank_account: false,
        iban: false,
        beneficiary: false,
        street: false,
        zip: false,
        city: false,
        firstname: false,
        lastname: false,
        phone: false,
        email: false,
        project_title: false,
        justification_funds: false,
        project_beneficiaries: false,
        proportion_residents_benefit_program: false,
        project_time: false,
        project_cost_total: false,
        project_own_contribution: false,
        project_contribution_requested: false,
        project_contribution_further_requested: false,
        project_income: false,
        file_portrait: false,
        file_annual_report: false,
        file_annual_financial_report: false,
        file_budget: false,
        file_bylaws: false,
        file_project_description: false,
        file_project_estimated_costs: false,
      },

      // Routes
      routes: {
        post: '/form/submit',
        deleteUpload: '/file/upload',
      },

      // Dropzone config
      config: {
        url: '/file/upload',
        method: 'post',
        maxFilesize: 16,
        maxFiles: 1,
        createImageThumbnails: false,
        autoProcessQueue: true,
        acceptedFiles: '.pdf, .doc, .docx, .xls, .xlsx, .zip',
        previewTemplate: this.uploadTemplate(),
        headers: {
          'x-csrf-token': document.head.querySelector('meta[name="csrf-token"]').content
        }
      },

      // States
      isSaving: false,
      isSuccess: false,
    }
  },

  created() {
    NProgress.configure({ showBar: false });
  },

  methods: {

    submit() {
      this.isSaving = true;
      NProgress.start();
      this.axios.post(this.routes.post, this.form).then(response => {
        this.isSaving = false;
        this.isSuccess = true;
        this.reset();
        NProgress.done();
      });
    },

    reset() {
      this.form = {
        files: {
          portrait: null,
          annual_report: null,
          annual_financial_report: null,
          budget: null,
          bylaws: null,
          project_description: null,
          project_estimated_costs: null,
        },
        project_add_instit_2: null,
        project_add_instit_3: null,
        project_add_instit_4: null,
        project_add_instit_5: null,
        project_add_instit_total_2: null,
        project_add_instit_total_3: null,
        project_add_instit_total_4: null,
        project_add_instit_total_5: null,
        project_add_instit_answer_2: 'offen',
        project_add_instit_answer_3: 'offen',
        project_add_instit_answer_4: 'offen',
        project_add_instit_answer_5: 'offen',
      }
    },

    /** File upload */
    uploadSending() {
      NProgress.start();
    },

    uploadSuccess(file, key = null, title = null) {
      const res = JSON.parse(file.xhr.response);
      const data = {
        name: res.name,
        title: title,
      };
      this.form.files[key] = data;
      this.errors[`file_${key}`] = false;
      NProgress.done();
    },

    uploadComplete(file) {
      if (file.status == "error") {
        if (file.xhr !== undefined) {
          let res = JSON.parse(file.xhr.response);
          if (res.errors.file) {
            res.errors.file.forEach(error => console.log(error));
            this.$refs.dropzone.removeFile(file);
          }
        }
        else {
          if (file.accepted == false) {
            if (file.size > 9000000) {
              alert('image_exceeds_max_size');
            }
            else {
              alert('image_type_not_allowed');
            }

            this.$refs.dropzone.removeFile(file);
          }
        }
      }
      // this.$refs.dropzone.removeFile(file);

    },

    uploadDelete(filename, key) {
      NProgress.start();
      this.axios.delete(`${this.routes.deleteUpload}/${filename}`).then(response => {
        this.form.files[key] = null;
        NProgress.done();
      });
    },

    uploadMaxFilesExceeded(file) {
      this.$refs.dropzone.removeAllFiles(true);
      alert('image_max_files_exceeded')
    },

    uploadTemplate: function () {
      return `<div></div>`;
    },

    /** Validation */
    validate(event) {
      if (event.target.value.length > 0) {
        event.target.classList.remove('is-invalid');
        this.hasErrors = false;
        return;
      }
      event.target.classList.add('is-invalid');
      this.hasErrors = true;
    },

    removeError(field) {
      this.errors[field] = false;
    }
  },

  computed: {
    openAmount() {
      let amount = 0;

      if (this.form.project_cost_total > 0) {

        if (this.form.project_own_contribution) {
          amount = this.form.project_cost_total - this.form.project_own_contribution;
        }

        if (this.form.project_contribution_requested) {
          amount = amount - this.form.project_contribution_requested;
        }

        if (this.form.project_contribution_further_requested) {
          amount = amount - this.form.project_contribution_further_requested;
        }
      }
      return amount;
    }
  }
}
</script>
