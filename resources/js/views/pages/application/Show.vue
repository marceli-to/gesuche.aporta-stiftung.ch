<template>
<div>
  <site-header 
    :user="$store.state.user" 
    :view="'show'">
  </site-header>
  <site-main v-if="isFetched">
    <page-menu 
      :type="$route.params.type" 
      :uuid="$route.params.uuid"
      :application="data" 
    ></page-menu>
    <application-wrapper>
      <application-grid>
        <div class="line-after">
          <h2>Organisation</h2>
          <application-row>
            <div class="span-1"><label>Name</label></div>
            <div class="span-3">{{data.name}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Kontakt</label></div>
            <div class="span-3">{{data.applicant_name}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Strasse, Nr.</label></div>
            <div class="span-3">
              {{data.street}}
              <template v-if="data.street_number">
                {{data.street_number}}
              </template>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Postleitzahl</label></div>
            <div class="span-3">{{data.zip}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Ort</label></div>
            <div class="span-3">{{data.city}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Webseite</label></div>
            <div class="span-3">{{data.website}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Telefon</label></div>
            <div class="span-3">{{data.phone}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>E-Mail</label></div>
            <div class="span-3">{{data.email}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Rechtsform</label></div>
            <div class="span-3">{{data.legal_form}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>IBAN</label></div>
            <div class="span-3">{{data.iban}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Begünstigter</label></div>
            <div class="span-3">{{data.beneficiary}}</div>
          </application-row>
          <h2>Serienbrief</h2>
          <application-row>
            <div class="span-1"><label>Anrede</label></div>
            <div class="span-3">{{data.salutation}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>IBAN</label></div>
            <div class="span-3">{{data.iban}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Textblock<br>Zusage</label></div>
            <div class="span-3">
              <span v-if="data.textblock_approval">{{data.textblock_approval}}</span>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Textblock<br>Zusage (Begründung)</label></div>
            <div class="span-3">
              <span v-if="data.textblock_justification">{{data.textblock_justification}}</span>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row class="mt-3x">
            <div class="span-1"><label>Textblock<br>Absage</label></div>
            <div class="span-3">
              <span v-if="data.textblock_denial">{{data.textblock_denial}}</span>
              <span v-else>–</span>
            </div>
          </application-row>
          <h2>Dokumente</h2>

          <application-row v-for="file in data.files" :key="file.uuid">
            <div class="span-1"><label>{{file.title}}</label></div>
            <div class="span-3">
              <div class="flex justify-between">
                <a :href="`/download/${data.uuid}/${file.name}`" class="anchor-download" target="_blank" :title="file.title">
                  {{file.name | truncate(30, '...')}}
                </a>
              </div>
              <div v-if="file.comment" class="mt-2x pb-3x">
                <span class="text-grey" v-if="file.user">{{file.user.full_name}}:<br></span>
                {{file.comment}}
              </div>
            </div>
          </application-row>
          
        </div>
        <div class="line-after">
          <h2>Projekt</h2>
          <application-row>
            <div class="span-1"><label>Titel</label></div>
            <div class="span-3">{{data.project_title}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Dauer</label></div>
            <div class="span-3">{{data.project_time}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Begründung</label></div>
            <div class="span-3 pb-6x">{{data.justification_funds}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Begünstigte</label></div>
            <div class="span-3 pb-6x">{{data.project_beneficiaries}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Begünstigte Zürich</label></div>
            <div class="span-3">{{data.proportion_residents_benefit_program}}</div>
          </application-row>

          <h2>Bemerkungen</h2>
          <application-row class="mb-3x">
            <div class="span-1"><label>Anteil Stadtzürcher*-innen</label></div>
            <div class="span-3">{{data.remarks_percentage_of_residents_zurich}}</div>
          </application-row>
          <application-row class="mb-3x">
            <div class="span-1"><label>Direkter Nutzen Zielgruppe</label></div>
            <div class="span-3">{{data.remarks_direct_benefits_to_target_group}}</div>
          </application-row>
          <application-row class="mb-3x">
            <div class="span-1"><label>Ausser-ordentlichkeit Vorhaben</label></div>
            <div class="span-3">{{data.remarks_exceptionality_of_project ? 'Ja' : 'Nein'}}</div>
          </application-row>
          <application-row class="mb-3x">
            <div class="span-1"><label>Weitere relevante Informationen</label></div>
            <div class="span-3">{{data.remarks_additional_relevant_information}}</div>
          </application-row>
          <application-row class="mb-3x">
            <div class="span-1"><label>Inhaltliche Zuordnung</label></div>
            <div class="span-3">{{data.remarks_content_allocation}}</div>
          </application-row>
        </div>
        <div>
          <h2>Projektkosten und Finanzierung</h2>
          <application-row>
            <div class="span-3"><label>Total</label></div>
            <div class="span-1 flex justify-end">{{data.project_cost_total | currency}}</div>
          </application-row>
          <application-row>
            <div class="span-3"><label>Eigenleistungen</label></div>
            <div class="span-1 flex justify-end">{{data.project_own_contribution | currency}}</div>
          </application-row>
          <!--
          <application-row v-if="data.project_add_instit_final_total > 0">
            <div class="span-3"><label class="text-grey">Beiträge Dritter</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_final_total | currency}}</div>
          </application-row>
          -->
          <application-row v-if="data.project_add_instit_total_2 > 0">
            <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_2}}</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_2 | currency}}</div>
          </application-row>
          <application-row v-if="data.project_add_instit_total_3 > 0">
            <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_3}}</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_3 | currency}}</div>
          </application-row>
          <application-row v-if="data.project_add_instit_total_4 > 0">
            <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_4}}</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_4 | currency}}</div>
          </application-row>
          <application-row v-if="data.project_add_instit_total_5 > 0">
            <div class="span-3 text-grey"><label class="text-grey">{{data.project_add_instit_5}}</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_total_5 | currency}}</div>
          </application-row>
          <application-row>
            <div class="span-3 text-grey"><label>Budgetierter jährlicher Ertrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_income | currency}}</div>
          </application-row>
          <application-row>
            <div class="span-3 text-grey"><label>Beantragter Beitrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_contribution_requested | currency}}</div>
          </application-row> 
          <application-row v-if="data.is_approved_external">
            <div class="span-3 text-grey"><label>Provisorisch genehmigter Beitrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_contribution_approved_temporary | currency}}</div>
          </application-row> 
          <application-row v-if="data.is_approved_external && $store.state.user.admin || data.is_denied_external && $store.state.user.admin">
            <div class="span-3 text-grey"><label>Genehmigter Beitrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_contribution_approved | currency}}</div>
          </application-row>
          <application-row v-if="data.is_approved">
            <div class="span-3 text-grey"><label><strong>Definitiv genehmigter Beitrag</strong></label></div>
            <div class="span-1 flex justify-end text-primary "><strong>{{data.project_contribution_approved | currency}}</strong></div>
          </application-row>
          <div class="application-state mt-6x" v-if="data.is_approved">
            <strong>Das Gesuch wurde am {{data.approved_at}} durch den Stiftungsrat genehmigt.</strong>
          </div>

          <!-- is admin -->
          <div v-if="$store.state.user.admin">
            <div v-if="data.is_new">
              <div class="mb-3x">
                <a 
                  href="javascript:;" 
                  class="btn-secondary is-small mb-3x"
                  @click.prevent="dialogDeny()">
                  <span>Ablehnen</span>
                </a>
                <a 
                  href="javascript:;" 
                  class="btn-primary is-small"
                  @click.prevent="dialogApprove()">
                  <span>Gesuch vollständig</span>
                </a>
              </div>
            </div>
            <div class="application-state" v-else-if="data.is_denied">
              <strong>Das Gesuch wurde am {{data.denied_at}} von der Stiftung abgelehnt</strong>
            </div>
            <div class="application-state" v-else-if="data.is_in_process">
                <strong>Das Gesuch wurde am {{data.approved_at}} durch die Stiftung geprüft.</strong>
            </div>
            <div v-else-if="data.is_pending_approval">
              <div class="application-state">
                <strong>Das Gesuch wurde am {{data.approved_at}} durch die Stiftung geprüft.</strong>
              </div>
              <div class="mt-6x">
                <a href="javascript:;" class="btn-secondary is-small" @click.prevent="reverse()">
                  <span>Genehmigung rückgangig machen</span>
                </a>
              </div>
            </div>
            <div v-else-if="data.is_denied_external || data.is_approved_external">
              <div class="application-state mb-8x" v-if="data.is_denied_external">
                <strong>Das Gesuch wurde am {{data.denied_at}} von der Stadt abgelehnt.</strong>
              </div>
              <div class="application-state mb-8x" v-if="data.is_approved_external">
                <strong>Das Gesuch wurde am {{data.approved_at}} von der Stadt genehmigt.</strong>
              </div>
              <div class="mb-3x">
                <a href="javascript:;" class="btn-primary is-small" @click.prevent="dialogFinalize()">
                  <span>Definitiv bestätigen</span>
                </a>
              </div>
              <div>
                <a href="javascript:;" class="btn-tertiary is-small" @click.prevent="dialogDeny()">
                  <span>Ablehnen</span>
                </a>
              </div>
              <application-row class="mt-8x">
                <application-label :cls="'span-3'">Beitrag anpassen</application-label>
                <application-input :cls="'span-1'">
                  <input type="text" v-model="data.project_contribution_approved" class="align-right" required @blur="validate($event)">
                </application-input>
              </application-row>
              <a 
                href="javascript:;" 
                :class="[data.project_contribution_approved > 0 ? '' : 'disabled', 'btn-primary is-small']"
                @click.prevent="dialogFinalizeWithUpdate()">
                <span>Angepassten Beitrag definitiv genehmigen</span>
              </a>
            </div>
          </div>
          <!-- // is admin -->

          <!-- is editor -->
          <div v-else>
            <div v-if="data.is_pending_approval || data.is_in_process">
              <div class="mb-12x">
                <application-row>
                  <application-label :cls="'span-3'">Beitrag anpassen</application-label>
                  <application-input :cls="'span-1'">
                    <input type="text" v-model="data.project_contribution_approved_temporary" class="align-right" required @blur="validate($event)">
                  </application-input>
                </application-row>
                <a 
                  href="javascript:;" 
                  :class="[data.project_contribution_approved_temporary >= 0 ? '' : 'disabled', 'btn-tertiary is-small mb-3x']"
                  @click.prevent="save()">
                  <span>Angepassten Beitrag temporär speichern</span>
                </a>
              </div>
              <h2 class="mb-3x">Gesuch definitiv bestätigen oder ablehnen.</h2>
              <p>Wird ein Gesuch bestätigt oder abgelehnt, geht dieses zurück an die Stiftung zur weiteren Bearbeitung.</p>
              <div class="mb-3x">
                <a href="javascript:;" class="btn-primary is-small" @click.prevent="dialogApprove()">
                  <span>Bestätigen</span>
                </a>
              </div>
              <div>
                <a href="javascript:;" class="btn-tertiary is-small" @click.prevent="dialogDeny()">
                  <span>Ablehnen</span>
                </a>
              </div>
              <!-- <a 
                href="javascript:;" 
                :class="[data.project_contribution_approved_temporary > 0 ? '' : 'disabled', 'btn-primary is-small']"
                @click.prevent="dialogApprove()">
                <span>Angepassten Beitrag bestätigen</span>
              </a> -->
            </div>
            <div v-else-if="data.is_approved_external">
              <div class="application-state">
                <strong>Das Gesuch wurde von der Stadt am {{ data.approved_at }} genehmigt.</strong>
              </div>
            </div>
            <div v-else-if="data.is_denied_external">
              <div class="application-state">
                <strong>Das Gesuch wurde von der Stadt am {{ data.denied_at }} abgelehnt.</strong>
              </div>
            </div>
          </div>
          <!-- // is editor -->

        </div>
      </application-grid>
    </application-wrapper>
  </site-main>
  <dialog-wrapper ref="dialogDeny">
    <template #message>
      <div><strong>Möchten Sie dieses Gesuch wirklich ablehnen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="deny()">Ja, ablehnen</a>
    </template>
  </dialog-wrapper>
  <dialog-wrapper ref="dialogApprove">
    <template #message>
      <div><strong>Möchten Sie dieses Gesuch wirklich genehmigen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="approve()">Ja, genehmigen</a>
    </template>
  </dialog-wrapper>
  <dialog-wrapper ref="dialogFinalize">
    <template #message>
      <div><strong>Möchten Sie dieses Gesuch definitiv genehmigen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="finalize()">Ja, genehmigen</a>
    </template>
  </dialog-wrapper>
  <dialog-wrapper ref="dialogFinalizeWithUpdate">
    <template #message>
      <div><strong>Möchten Sie dieses Gesuch mit angepasstem Beitrag definitiv genehmigen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="finalizeWithUpdate()">Ja, genehmigen</a>
    </template>
  </dialog-wrapper>
</div>
</template>
<script>
import NProgress from 'nprogress';
import Filter from "@/views/pages/application/mixins/Filter";
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import DialogWrapper from "@/components/ui/misc/Dialog.vue";
import PageMenu from '@/components/layout/Menu.vue';
import ApplicationWrapper from '@/components/layout/Wrapper.vue';
import ApplicationGrid from '@/components/layout/Grid.vue';
import ApplicationRow from '@/components/layout/Row.vue';
import ApplicationLabel from '@/components/layout/Label.vue';
import ApplicationInput from '@/components/layout/Input.vue';

export default {
  components: {
    NProgress,
    SiteHeader,
    SiteMain,
    PageMenu,
    ApplicationWrapper,
    ApplicationGrid,
    ApplicationRow,
    ApplicationInput,
    ApplicationLabel,
    DialogWrapper
  },

  mixins: [ErrorHandling, Filter],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        comments: [],
      },

      // Routes
      routes: {
        fetch: '/api/application',
        deny: '/api/application/deny',
        save: '/api/application/save',
        approve: '/api/application/approve',
        finalize: '/api/application/approve/final',
        reverse: '/api/application/reverse',
      },

      // States
      isFetched: true,
      isLoading: false,
      hasErrors: false,
    };
  },

  mounted() {
    this.fetch();
    NProgress.configure({ showBar: false });
  },

  methods: {

    fetch() {
      this.isFetched = false;
      NProgress.start();
      this.axios.get(`${this.routes.fetch}/${this.$route.params.uuid}`).then(response => {
        this.data = response.data;
        NProgress.done();
        this.isFetched = true;
        this.updateFilterMenu(this.$route.params.uuid);
      });
    },

    validate(event) {
      if (event.target.value.length > 0) {
        event.target.classList.remove('is-invalid');
        this.hasErrors = false;
        return;
      }
      event.target.classList.add('is-invalid');
      this.hasErrors = true;
    },

    dialogDeny() {
      this.$refs.dialogDeny.show();
    },

    dialogApprove() {
      this.$refs.dialogApprove.show();
    },

    dialogFinalize() {
      this.$refs.dialogFinalize.show();
    },

    dialogFinalizeWithUpdate() {
      this.$refs.dialogFinalizeWithUpdate.show();
    },

    deny() {
      this.axios.get(`${this.routes.deny}/${this.$route.params.uuid}`).then(response => {
        this.$refs.dialogDeny.hide();
        this.fetch();
      });
    },

    save() {
      let data = {
        project_contribution_approved_temporary: this.data.project_contribution_approved_temporary
      };
      this.axios.put(`${this.routes.save}/${this.$route.params.uuid}`, data).then(response => {
        this.fetch();
      });
    },

    approve() {

      let data = {};
      if (!this.$store.state.user.admin) {
        data = { project_contribution_approved_temporary: this.data.project_contribution_approved_temporary };
      }
      this.axios.put(`${this.routes.approve}/${this.$route.params.uuid}`, data).then(response => {
        this.$refs.dialogApprove.hide();
        this.fetch();
      });
    },

    finalize() {
      this.axios.put(`${this.routes.finalize}/${this.$route.params.uuid}`).then(response => {
        this.$refs.dialogFinalize.hide();
        this.fetch();
      });
    },

    finalizeWithUpdate() {
      let data = {project_contribution_approved: this.data.project_contribution_approved};
      this.axios.put(`${this.routes.finalize}/${this.$route.params.uuid}`, data).then(response => {
        this.$refs.dialogFinalizeWithUpdate.hide();
        this.fetch();
      });
    },

    reverse() {
      this.axios.get(`${this.routes.reverse}/${this.$route.params.uuid}`).then(response => {
        this.fetch();
      });
    },
  },
  watch: {
    '$route'() {
      this.fetch();
    }
  }

};
</script>
