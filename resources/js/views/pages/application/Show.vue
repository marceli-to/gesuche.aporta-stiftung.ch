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
            <div class="span-1"><label>Adresse</label></div>
            <div class="span-3" v-html="data.applicant_address"></div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Webseite</label></div>
            <div class="span-3">{{data.website}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Kontakt</label></div>
            <div class="span-3">{{data.applicant_name}}</div>
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
            <div class="span-1"><label>Konto</label></div>
            <div class="span-3 pb-6x">{{data.bank_account}}</div>
            <div class="span-3 start-2">
              <div>
                Formulierung im Serienbrief<br><br>
                Wir bitten Sie, uns den Eingang des erwähnten Betrages, welchen wir Ihnen bis Ende<br><br>
                <span v-if="data.financial_periode">{{data.financial_periode}}</span>
                <span v-else>[Monat Jahr]</span><br><br>
                auf Ihr<br><br>
                <span v-if="data.financial_account">{{data.financial_account}}</span>
                <span v-else>[Kontoangaben]</span><br><br>
                überweisen werden, umgehend schriftlich zu bestätigen.
              </div>
            </div>
          </application-row>
          <h2>Dokumente</h2>
          <application-row>
            <div class="span-1"><label>Portrait</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_portrait}`" class="anchor-download" target="_blank" title="Download Portrait" v-if="data.file_portrait">
                {{data.file_portrait | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Jahresbericht</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_annual_report}`" class="anchor-download" target="_blank" title="Download Jahresbericht" v-if="data.file_annual_report">
                {{data.file_annual_report | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Jahresrechnung</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_annual_financial_report}`" class="anchor-download" target="_blank" title="Download Jahresrechnung" v-if="data.file_annual_financial_report">
                {{data.file_annual_financial_report | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Budget</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_budget}`" class="anchor-download" target="_blank" title="Download Budget" v-if="data.file_budget">
                {{data.file_budget | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Statuen</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_bylaws}`" class="anchor-download" target="_blank" title="Download Statuen" v-if="data.file_bylaws">
                {{data.file_bylaws | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Projekt</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_project_description}`" class="anchor-download" target="_blank" title="Download Projekt" v-if="data.file_project_description">
                {{data.file_project_description | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>KV</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_project_estimated_costs}`" class="anchor-download" target="_blank" title="Download KV" v-if="data.file_project_estimated_costs">
                {{data.file_project_estimated_costs | truncate(40, '...')}}
              </a>
              <span v-else>–</span>
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
          <application-row v-if="data.project_add_instit_final_total > 0">
            <div class="span-3"><label class="text-grey">Beiträge Dritter</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_add_instit_final_total | currency}}</div>
          </application-row>
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
            <div class="span-3 text-grey"><label>Beantragter Betrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_contribution_requested | currency}}</div>
          </application-row> 

          <div v-if="$store.state.user.admin">
            <div class="mb-12x" v-if="data.is_new">
              <a href="javascript:;" class="btn-secondary is-small" @click.prevent="dialogReject()">
                <span>Ablehnen</span>
              </a>
            </div>
            <div v-if="data.is_rejected_internal" class="text-danger align-center">
              <strong>Das Gesuch wurde von der Stiftung abgelehnt</strong>
            </div>
            <div v-else-if="data.is_rejected_external" class="text-danger align-center">
              <strong>Das Gesuch wurde von der Stadt Zürich abgelehnt</strong>
            </div>
            <div v-else-if="data.is_approved_internal">
              <application-row>
                <div class="span-3 text-grey"><label>Vorgeschlagener Betrag</label></div>
                <div class="span-1 flex justify-end">{{data.project_contribution_proposed | currency}}</div>
              </application-row>
              <application-row>
                <div class="span-3 text-grey"><label>Genehmigter Betrag Stadt Zürich</label></div>
                <div class="span-1 flex justify-end">{{data.project_contribution_approved | currency}}</div>
              </application-row>
              <div>
                <strong>Das Gesuch wurde von der Stiftung am {{ data.approved_at }} genehmigt</strong>
              </div>
            </div>
            <div v-else>
              <application-row>
                <application-label :cls="'span-3'">Vorgeschlagener Betrag</application-label>
                <application-input :cls="'span-1'">
                  <input type="text" v-model="data.project_contribution_proposed" class="align-right" required @blur="validate($event)">
                </application-input>
              </application-row>
              <application-row v-if="!$store.state.user.admin">
                <div class="span-3 text-grey"><label>Genehmigter Betrag</label></div>
                <div class="span-1 flex justify-end text-grey">{{data.project_contribution_approved | currency}}</div>
              </application-row>
              <div>
                <a 
                  href="javascript:;" 
                  :class="[data.project_contribution_proposed > 0 ? 'btn-primary is-small mb-3x' : 'btn-primary disabled is-small mb-3x']"
                  @click.prevent="dialogApprove()">
                  <span>Genehmigen</span>
                </a>
              </div>
            </div>
          </div>
          <div v-else>
            <div v-if="data.is_approved_internal">
              <div class="mb-12x">
                <a href="javascript:;" class="btn-secondary is-small" @click.prevent="dialogReject()">
                  <span>Ablehnen</span>
                </a>
              </div>
              <application-row>
                <application-label :cls="'span-3'">Vorgeschlagener Betrag</application-label>
                <div class="span-1 flex justify-end text-grey">{{data.project_contribution_proposed | currency}}</div>
              </application-row>
              <application-row>
                <application-label :cls="'span-3'">Genehmigter Betrag</application-label>
                <application-input :cls="'span-1'">
                  <input type="text" v-model="data.project_contribution_approved" class="align-right" required @blur="validate($event)">
                </application-input>
              </application-row>
              <a 
                href="javascript:;" 
                :class="[data.project_contribution_approved > 0 ? 'btn-primary is-small mt-3x' : 'btn-primary disabled is-small mb-3x']"
                @click.prevent="dialogApprove()">
                <span>Genehmigen</span>
              </a>
            </div>
            <div v-else-if="data.is_rejected_external" class="text-danger align-center">
              <strong>Das Gesuch wurde von der Stadt Zürich abgelehnt</strong>
            </div>
            <div v-else-if="data.is_approved_external">
              <application-row>
                <div class="span-3 text-grey"><label>Vorgeschlagener Betrag</label></div>
                <div class="span-1 flex justify-end">{{data.project_contribution_proposed | currency}}</div>
              </application-row>
              <application-row>
                <div class="span-3 text-grey"><label>Genehmigter Betrag Stadt Zürich</label></div>
                <div class="span-1 flex justify-end">{{data.project_contribution_approved | currency}}</div>
              </application-row>
              <div>
                <strong>Das Gesuch wurde von der Stiftung am {{ data.approved_at }} genehmigt</strong>
              </div>
            </div>
          </div>
        </div>
      </application-grid>
    </application-wrapper>
  </site-main>
  <dialog-wrapper ref="dialogReject">
    <template #message>
      <div><strong>Möchten Sie dieses Gesuch wirklich ablehnen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="reject()">Ja, ablehnen</a>
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
</div>
</template>
<script>
import NProgress from 'nprogress';
import Filter from "@/views/pages/application/mixins/Filter";
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import DialogWrapper from "@/components/ui/misc/Dialog.vue";
import PageMenu from '@/views/pages/application/components/Menu.vue';
import ApplicationWrapper from '@/views/pages/application/components/Wrapper.vue';
import ApplicationGrid from '@/views/pages/application/components/Grid.vue';
import ApplicationRow from '@/views/pages/application/components/Row.vue';
import ApplicationLabel from '@/views/pages/application/components/Label.vue';
import ApplicationInput from '@/views/pages/application/components/Input.vue';

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
      data: {},

      // Routes
      routes: {
        fetch: '/api/application',
        reject: '/api/application/reject',
        approve: '/api/application/approve',
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

    dialogReject() {
      this.$refs.dialogReject.show();
    },

    dialogApprove() {
      this.$refs.dialogApprove.show();
    },

    reject() {
      this.axios.get(`${this.routes.reject}/${this.$route.params.uuid}`).then(response => {
        this.$refs.dialogReject.hide();
        this.fetch();
      });
    },

    approve() {

      let data = {};
      if (this.$store.state.user.admin) {
        data = { project_contribution_proposed: this.data.project_contribution_proposed };
      }
      else {
        data = { project_contribution_approved: this.data.project_contribution_approved };
      }

      this.axios.put(`${this.routes.approve}/${this.$route.params.uuid}`, data).then(response => {
        this.$refs.dialogApprove.hide();
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
