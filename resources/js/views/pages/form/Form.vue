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
          <h1 class="span-full">Formular Beitragsgesuche Stadt Zürich</h1>
          <p class="span-full mb-10x">Gesuche für den Kanton Graubünden sind an ... zu richten</p>

          <div class="span mb-5x">
            <application-row class="application-row__form mb-3x">
              <application-label :cls="'span-8 sm:span-9'">
                Ist die Institution im Gesuchsjahr steuerberfreit?
              </application-label>
              <application-input :cls="'span-4 sm:span-3'">
                <div class="span flex items-start">
                  <div class="mt-1x flex items-center">
                    <input type="radio" id="isTaxExempt-yes" name="isTaxExempt" v-bind:value="1" v-model="isTaxExempt" />
                    <label class="ml-2x" for="isTaxExempt-yes">Ja</label>
                  </div>
                  <div class="ml-3x mt-1x flex items-center">
                    <input type="radio" id="isTaxExempt-no" name="isTaxExempt" v-bind:value="0" v-model="isTaxExempt" />
                    <label class="ml-2x" for="isTaxExempt-no">Nein</label>
                  </div>
                </div>
              </application-input>
            </application-row>
            <application-row class="application-row__form mb-3x">
              <application-label :cls="'span-8 sm:span-9'">
                Sind die <button type="button" class="info-link" @click="showGeoDialog">geografischen</button> und <button type="button" class="info-link" @click="showIdealDialog">ideellen</button> Bedingungen für Beitragsgesuche laut unserer Webseite gegeben?
              </application-label>
              <application-input :cls="'span-4 sm:span-3'">
                <div class="span flex items-start">
                  <div class="mt-1x flex items-center">
                    <input type="radio" id="isIdealistic-yes" name="isIdealistic" v-bind:value="1" v-model="isIdealistic"/>
                    <label class="ml-2x" for="isIdealistic-yes">Ja</label>
                  </div>
                  <div class="ml-3x mt-1x flex items-center">
                    <input type="radio" id="isIdealistic-no" name="isIdealistic" v-bind:value="0" v-model="isIdealistic" />
                    <label class="ml-2x" for="isIdealistic-no">Nein</label>
                  </div>
                </div>
              </application-input>
            </application-row>
            <application-row class="application-row__form mb-3x">
              <application-label :cls="'span-8 sm:span-9'">
                Handelt es sich um ein Projekt mit <button type="button" class="info-link" @click="showExtraordinaryDialog">ausserordentlichem</button> Charakter?
              </application-label>
              <application-input :cls="'span-4 sm:span-3'">
                <div class="span flex items-start">
                  <div class="mt-1x flex items-center">
                    <input type="radio" id="isExtraordinary-yes" name="isExtraordinary" v-bind:value="1" v-model="isExtraordinary" />
                    <label class="ml-2x" for="isExtraordinary-yes">Ja</label>
                  </div>
                  <div class="ml-3x mt-1x flex items-center">
                    <input type="radio" id="isExtraordinary-no" name="isExtraordinary" v-bind:value="0" v-model="isExtraordinary" />
                    <label class="ml-2x" for="isExtraordinary-no">Nein</label>
                  </div>
                </div>
              </application-input>
            </application-row>
            <application-row class="application-row__form mb-3x">
              <application-label :cls="'span-8 sm:span-9'">
               Unterstützt das Projekt grösstenteils Bewohnende der Stadt Zürich?
              </application-label>
              <application-input :cls="'span-4 sm:span-3'">
                <div class="span flex items-start">
                  <div class="mt-1x flex items-center">
                    <input type="radio" id="isSupportingResidents-yes" name="isSupportingResidents" v-bind:value="1" v-model="isSupportingResidents" />
                    <label class="ml-2x" for="isSupportingResidents-yes">Ja</label>
                  </div>
                  <div class="ml-3x mt-1x flex items-center">
                    <input type="radio" id="isSupportingResidents-no" name="isSupportingResidents" v-bind:value="0" v-model="isSupportingResidents" />
                    <label class="ml-2x" for="isSupportingResidents-no">Nein</label>
                  </div>
                </div>
              </application-input>
            </application-row>
          </div>

          <template v-if="fulfills_requirements">
            <template>
              <h2 class="span">Institution</h2>
              <div class="span">
                <application-row :class="[errors.name ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.name ? errors.name : 'Rechtlicher Name der Institution *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <input type="text" v-model="form.name" required :class="[errors.name ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('name')">
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row class="application-row__form">
                  <application-label :cls="'span-4 sm:span-5'">
                  Gab es in den letzten 10 Jahren eine Namensänderung? *
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <select v-model="form.name_change" required>
                      <option value="no">Nein</option>
                      <option value="yes">Ja</option>
                    </select>
                  </application-input>
                </application-row>
              </div>

              <template v-if="form.name_change == 'yes'">
                <div class="span">
                  <application-row :class="[errors.former_name ? 'has-error' : '', 'application-row__form']">
                    <application-label :cls="'span-4 sm:span-5'">
                      {{ errors.former_name ? errors.former_name : 'Vorheriger rechtlicher Name der Institution *' }}
                    </application-label>
                    <application-input :cls="'span-8 sm:span-7'">
                      <input type="text" v-model="form.former_name" required :class="[errors.former_name ? 'is-invalid' : '', '']" @blur="validate($event)" @focus="removeError('former_name')">
                    </application-input>
                  </application-row>
                </div>
              </template>

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
                    <the-mask 
                      type="text" 
                      v-model="form.iban" 
                      required 
                      :class="[errors.iban ? 'is-invalid' : '', '']" 
                      @blur="validate($event)" 
                      @focus="removeError('iban')" 
                      :mask="['XXXX XXXX XXXX XXXX XXXX X']" />
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row :class="[errors.beneficiary ? 'has-error' : '', 'application-row__form']">
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
              <h2 class="span">Angeben zur Institution</h2>
              <div class="span mb-3x">
                <application-row :class="[errors.portrait ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.portrait ? errors.portrait : 'Kurzportrait *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <textarea v-model="form.portrait" maxlength="500" required :class="[errors.portrait ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('portrait')"></textarea>
                    <text-length-indicator :field="form.portrait" />
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row :class="[errors.file_tax_exemption ? 'has-error' : '', 'application-row__form']">
                  <div class="span-4 sm:span-5">
                    <label>
                      {{ errors.file_tax_exemption ? errors.file_tax_exemption : 'Aktuelle Steuerbefreiung (PDF) *' }}
                    </label>
                  </div>
                  <div class="span-8 sm:span-7">
                    <template v-if="form.files.tax_exemption">
                      <div class="flex justify-between">
                        <a :href="`/storage/uploads/temp/${form.files.tax_exemption.name}`" class="anchor-download" target="_blank">
                          {{form.files.tax_exemption.name | truncate(80, '...')}}
                        </a>
                        <a 
                          href="javascript:;" 
                          @click="uploadDelete(form.files.tax_exemption.name, 'tax_exemption')" 
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
                        @vdropzone-success="uploadSuccess($event, 'tax_exemption', 'Steuerbefreiung')"
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
                      {{ errors.file_annual_report ? errors.file_annual_report : 'Letzter Jahresbericht (PDF) *' }}
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
                      {{ errors.file_annual_financial_report ? errors.file_annual_financial_report : 'Letzte Jahresrechnung (PDF) *' }}
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
                      {{ errors.file_budget ? errors.file_budget : 'Aktuelles Budget (PDF) *' }}
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
              <h2 class="span">Angaben zum Projekt</h2>
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
                    {{ errors.proportion_residents_benefit_program ? errors.proportion_residents_benefit_program : '% Anteil begünstigte Stadtzürcherinnen und Stadtzürcher (falls quantifizierbar) *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <input type="text" v-model="form.proportion_residents_benefit_program" required :class="[errors.proportion_residents_benefit_program ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('proportion_residents_benefit_program')" />
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
              <div class="span mb-6x">
                <application-row :class="[errors.project_description ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_description ? errors.project_description : 'Beschreibung und Begründung des Projekts *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <textarea v-model="form.project_description" maxlength="3500" required :class="[errors.project_description ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_description')"></textarea>
                    <text-length-indicator :field="form.project_description" :max-chars="3500" />
                  </application-input>
                </application-row>
              </div>
              <!-- 
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
              </div> // removed, 22.08.2025, ms -->
            </template>

            <template>
              <h2 class="span">Projektkosten und -finanzierung</h2>
              <div class="span">
                <application-row :class="[errors.project_cost_total ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_cost_total ? errors.project_cost_total : 'Projektkosten Total (CHF) *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <number v-model="form.project_cost_total" v-bind="number" required :class="[errors.project_cost_total ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_cost_total')" />
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row :class="[errors.project_own_contribution ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_own_contribution ? errors.project_own_contribution : 'Eigenleistungen Total (CHF) *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <number v-model="form.project_own_contribution" v-bind="number" required :class="[errors.project_own_contribution ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_own_contribution')" />
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row :class="[errors.project_contribution_requested ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_contribution_requested ? errors.project_contribution_requested : 'Beantragter Beitrag Dr. Stephan à Porta-Stiftung (CHF) *' }}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <number v-model="form.project_contribution_requested" v-bind="number" required :class="[errors.project_contribution_requested ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_contribution_requested')" />
                  </application-input>
                </application-row>
              </div>

              <!--
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
              -->

            </template>

            <template>
              <p class="span mt-8x">Sofern Sie für das gleiche Gesuch weitere Institutionen um einem Beitrag ersuchen, bitten wir um folgende Angaben:</p>
              <template v-if="errors.project_add_instit_2 || errors.project_add_instit_total_2 || errors.project_add_instit_3 || errors.project_add_instit_total_3 || errors.project_add_instit_4 || errors.project_add_instit_total_4 || errors.project_add_instit_5 || errors.project_add_instit_total_5">
                <p class="has-error">Es sind nicht alle Felder korrekt ausgefüllt:</p>
              </template>
              <div class="span">
                <application-row class="application-row__form">
                  <application-input :cls="'span-4 sm:span-4'">
                    <label class="mb-2x">Institution</label>
                    <input 
                      :tabindex="index"
                      class="mb-2x"
                      type="text" 
                      v-model="form[`project_add_instit_${add}`]" 
                      :class="[errors[`project_add_instit_${add}`] ? 'is-invalid' : '', ''] " @focus="removeError([`project_add_instit_${add}`])"
                      v-for="(add, index) in [2, 3, 4, 5]" :key="index">
                  </application-input>
                  <application-input :cls="'span-4 sm:span-4'">
                    <label class="mb-2x">Betrag</label>

                    <number 
                      v-model="form[`project_add_instit_total_${add}`]" 
                      v-bind="number" 
                      :class="[errors[`project_add_instit_total_${add}`] ? 'is-invalid' : '', 'mb-2x'] " 
                      @focus="removeError([`project_add_instit_total_${add}`])" 
                      v-for="(add, index) in [2, 3, 4, 5]" :key="index" />

                  </application-input>
                  <application-input :cls="'span-4 sm:span-4'">
                    <label class="mb-2x">Antwort</label>
                    <div class="select-wrapper mb-2x" v-for="(add, index) in [2, 3, 4, 5]" :key="index">
                      <select v-model="form[`project_add_instit_answer_${add}`]" :tabindex="index">
                        <option :value="'Zusage'">Zusage</option>
                        <option :value="'Absage'">Absage</option>
                        <option :value="'offen'">offen</option>
                      </select>
                    </div>
                  </application-input>
                </application-row>

              </div>
              <div class="span mt-6x">
                <application-row class="application-row__form">
                  <application-label :cls="'span-4 sm:span-5'">
                    <template v-if="openAmount == 0">Über- oder Unterfinanzierung (CHF)</template>
                    <template v-if="openAmount < 0">Über- oder <strong>Unterfinanzierung</strong> (CHF)</template>
                    <template v-if="openAmount > 0"><strong>Über-</strong> oder Unterfinanzierung (CHF)</template>
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <number :value="openAmount" v-bind="number" readonly />
                  </application-input>
                </application-row>
              </div>
              <div class="span">
                <application-row class="application-row__form" v-if="openAmount < 0">
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
              <h2 class="span">Budgetierte Einnahmen aus dem Projekt</h2>
              <div class="span">
                <application-row :class="[errors.project_income ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_income ? errors.project_income : 'Budgetierte Einnahmen (CHF) *'}}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <number v-model="form.project_income" v-bind="number" required :class="[errors.project_income ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_income')" />
                  </application-input>
                </application-row>

                <application-row :class="[errors.project_income_remarks ? 'has-error' : '', 'application-row__form']">
                  <application-label :cls="'span-4 sm:span-5'">
                    {{ errors.project_income_remarks ? errors.project_income_remarks : ' Wie kommen die Einnahmen zustande? *'}}
                  </application-label>
                  <application-input :cls="'span-8 sm:span-7'">
                    <textarea v-model="form.project_income_remarks" maxlength="200" style="min-height: 100px;" required :class="[errors.project_income_remarks ? 'is-invalid' : '', ''] " @blur="validate($event)" @focus="removeError('project_income_remarks')"></textarea>
                    <text-length-indicator :field="form.project_income_remarks" :maxChars="200" />
                  </application-input>
                </application-row>

              </div>

            </template>
            <template>
              <div class="span mt-12x">
                <application-row :class="[errors.confirm_correctness ? 'has-error' : '', 'application-row__form ']">
                  <div class="span-full flex items-center">
                    <input type="checkbox" v-model="form.confirm_correctness" id="confirm_correctness" required :class="[errors.confirm_correctness ? 'is-invalid' : '', '']" @change="removeError('confirm_correctness')" />
                    <label class="ml-2x" for="confirm_correctness">
                      {{ errors.confirm_correctness ? errors.confirm_correctness : 'Die oben gemachten Angaben sind vollständig und entsprechen der Wahrheit.' }}
                    </label>
                  </div>
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

          <template v-else-if="showRequirementsNotFulfilled">
            <div class="span-full">
              <p class="span-full mb-10x text-danger ">
                <strong>Voraussetzungen nicht erfüllt. Ihr Gesuch kann leider nicht entgegengenommen werden.</strong>
              </p>
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

  <dialog-wrapper ref="geoDialog" message-class="is-left">
    <template #message>
      <h2 class="mb-2x">Geografische Bedingungen</h2>
      <p>Die Stiftungsurkunde sieht vor, dass nur Projekte berücksichtigt werden, die grösstenteils den Bewohnerinnen und Bewohnern der Stadt Zürich resp. des Kantons Graubünden zugutekommen.</p>
    </template>
  </dialog-wrapper>

  <dialog-wrapper ref="idealDialog" message-class="is-left">
    <template #message>
      <h2 class="mb-2x">Ideelle Bedingungen</h2>
      <p>Bei den Gesuchstellenden muss es sich um juristische Personen wie Vereine oder Stiftungen mit wohltätigem und/oder gemeinnützigem Zweck handeln. Die Gesuchstellenden Organisationen müssen von den Staats- und Gemeindesteuern befreit sein. Berücksichtigt werden vor allem Organisationen, die Menschen helfen und zur Verbesserung von deren Lebensqualität beitragen (z.B. sozial und gesundheitlich Bedürftige, Menschen mit einer Beeinträchtigung, Suchtkranke, Betagte, Asylsuchende, Migrantinnen und Migranten). Institutionen mit kulturellen Zielsetzungen oder Privatpersonen können daher nicht unterstützt werden.</p>
    </template>
  </dialog-wrapper>

  <dialog-wrapper ref="extraordinaryDialog" message-class="is-left">
    <template #message>
      <h2 class="mb-2x">Projekte mit ausserordentlichem Charakter</h2>
      <p>Im Interesse einer gezielten Unterstützung werden vor allem Projekte unterstützt, die den üblichen Rahmen der Tätigkeit der Gesuchstellenden sprengen und damit den Charakter von ausserordentlichen Ausgaben haben. Infrage kommen z.B. Um- oder Erweiterungsbauten, Anschaffungen besonderer Art oder Vorhaben in den Bereichen Projektentwicklung, Innovation, Evaluation, Organisationsentwicklung oder Werbekampagnen. Beiträge zur Deckung von Betriebsdefiziten werden selbst in begründeten Fällen nur ausnahmsweise und einmalig gewährt. Die Dr. Stephan à Porta-Stiftung übernimmt keine Defizitgarantien. Zudem werden keine Projekte unterstützt, die bereits realisiert sind.</p>
    </template>
  </dialog-wrapper>
</div>
</template>

<script>
import NProgress from 'nprogress';
import ErrorHandling from '@/mixins/ErrorHandling';
import {TheMask} from 'vue-the-mask';
import { component as number } from '@coders-tm/vue-number-format';
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
    TextLengthIndicator,
    TheMask,
    number
  },

  mixins: [ErrorHandling],

  data() {
    return {
      
      form: {
        confirm_correctness: false,
        name_change: 'no',
        former_name: '',
        portrait: '',
        project_cost_total: '',
        project_own_contribution: '',
        project_contribution_requested: '',
        project_income: '',
        project_description: '',
        files: {
          // portrait: null,
          tax_exemption: null,
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
        project_add_instit_total_2: '',
        project_add_instit_total_3: '',
        project_add_instit_total_4: '',
        project_add_instit_total_5: '',
        project_add_instit_answer_2: 'offen',
        project_add_instit_answer_3: 'offen',
        project_add_instit_answer_4: 'offen',
        project_add_instit_answer_5: 'offen',
      },

      errors: {
        confirm_correctness: false,
        name: false,
        name_change: false,
        former_name: false,
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
        portrait: false,
        project_title: false,
        project_description: false,
        justification_funds: false,
        project_beneficiaries: false,
        proportion_residents_benefit_program: false,
        project_time: false,
        project_cost_total: false,
        project_own_contribution: false,
        project_contribution_requested: false,
        //project_contribution_further_requested: false, // removed, unknown date, ms
        project_income: false,
        project_income_remarks: false,
        // file_portrait: false, // removed, 22.08.2025, ms
        file_tax_exemption: false,
        file_annual_report: false,
        file_annual_financial_report: false,
        file_budget: false,
        file_bylaws: false,
        // file_project_description: false, // removed, 22.08.2025, ms
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

      isTaxExempt: false,
      isIdealistic: false,
      isExtraordinary: false,
      isSupportingResidents: false,

      number: {
        decimal: '.',
        separator: "'",
        prefix: '',
        precision: 0,
        masked: false,
        nullValue: ''
      },

    }
  },

  created() {
    NProgress.configure({ showBar: false });
  },

  methods: {

    submit() {


      // Validate IBAN
      // if (this.form.iban && this.form.iban.length != 21) {
      //   this.$notify({ type: "error", text: `Bitte IBAN prüfen!`});
      //   this.errors.iban = 'IBAN ist ungültig';
      //   return;
      // }

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
        project_cost_total: '',
        project_own_contribution: '',
        project_contribution_requested: '',
        project_income: '',
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
        project_add_instit_total_2: '',
        project_add_instit_total_3: '',
        project_add_instit_total_4: '',
        project_add_instit_total_5: '',
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
              alert('Datei ist zu gross');
            }
            else {
              alert('Dateityp ist nicht erlaubt');
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
      alert('Dateimaxium erreicht')
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
    },

    showGeoDialog() {
      this.$refs.geoDialog.show();
    },

    showIdealDialog() {
      this.$refs.idealDialog.show();
    },

    showExtraordinaryDialog() {
      this.$refs.extraordinaryDialog.show();
    }
  },

  computed: {
    openAmount() {
      let amount = '';

      if (this.form.project_cost_total > 0) {

        amount = amount - this.form.project_cost_total;

        if (this.form.project_own_contribution) {
          amount = amount + parseInt(this.form.project_own_contribution);
        }

        if (this.form.project_contribution_requested) {
          amount = amount + parseInt(this.form.project_contribution_requested);
        }

        if (this.form.project_add_instit_total_2) {
          amount = amount + parseInt(this.form.project_add_instit_total_2);
        }

        if (this.form.project_add_instit_total_3) {
          amount = amount + parseInt(this.form.project_add_instit_total_3);
        }

        if (this.form.project_add_instit_total_4) {
          amount = amount + parseInt(this.form.project_add_instit_total_4);
        }

        if (this.form.project_add_instit_total_5) {
          amount = amount + parseInt(this.form.project_add_instit_total_5);
        }
      }
      return amount;
    },

    fulfills_requirements() {
      return this.isTaxExempt == 1 && 
             this.isIdealistic == 1 && 
             this.isExtraordinary == 1 && 
             this.isSupportingResidents == 1;
    },

    hasAnsweredRequirements() {
      return this.isTaxExempt !== false && 
             this.isIdealistic !== false && 
             this.isExtraordinary !== false && 
             this.isSupportingResidents !== false;
    },

    showRequirementsNotFulfilled() {
      return this.hasAnsweredRequirements && !this.fulfills_requirements;
    }
  },

  watch: {
    'form.iban': function (val) {
      this.errors.iban = false;
    }
  } 
}
</script>
