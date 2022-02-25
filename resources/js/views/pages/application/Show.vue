<template>
<div>
  <site-header :user="$store.state.user" class="is-detail"></site-header>
  <site-main v-if="isFetched">
    <page-menu :type="$route.params.type" :uuid="$route.params.uuid" />
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
            <div class="span-1"><label>Bankkonto</label></div>
            <div class="span-3">{{data.bank_account}}</div>
          </application-row>
          <h2>Dokumente</h2>
          <application-row>
            <div class="span-1"><label>Portrait</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_portrait}`" class="anchor-download" target="_blank" title="Download Portrait">
                {{data.file_portrait | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Jahresbericht</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_anunal_report}`" class="anchor-download" target="_blank" title="Download Jahresbericht">
                {{data.file_anunal_report | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Jahresrechnung</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_annual_financial_report}`" class="anchor-download" target="_blank" title="Download Jahresrechnung">
                {{data.file_annual_financial_report | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Budget</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_budget}`" class="anchor-download" target="_blank" title="Download Budget">
                {{data.file_budget | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Statuen</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_bylaws}`" class="anchor-download" target="_blank" title="Download Statuen">
                {{data.file_bylaws | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Projekt</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_project_description}`" class="anchor-download" target="_blank" title="Download Projekt">
                {{data.file_project_description | truncate(40, '...')}}
              </a>
            </div>
          </application-row>
          <application-row>
            <div class="span-1"><label>KV</label></div>
            <div class="span-3">
              <a :href="`/download/${data.uuid}/${data.file_project_estimated_costs}`" class="anchor-download" target="_blank" title="Download KV">
                {{data.file_project_estimated_costs | truncate(40, '...')}}
              </a>
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
            <div class="span-3 pb-8x">{{data.justification_funds}}</div>
          </application-row>
          <application-row>
            <div class="span-1"><label>Begünstigte</label></div>
            <div class="span-3">{{data.project_beneficiaries}}</div>
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
          <application-row>
            <div class="span-3 text-grey"><label>Genehmigter Betrag</label></div>
            <div class="span-1 flex justify-end text-grey">{{data.project_contribution_approved | currency}}</div>
          </application-row>

          <div>
            <a href="" class="btn-primary is-small mb-3x">
              <span>Genehmigen</span>
            </a>
            <a href="" class="btn-secondary is-small">
              <span>Ablehnen</span>
            </a>
          </div>
        </div>
      </application-grid>
    </application-wrapper>
  </site-main>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import SiteHeader from '@/views/layout/Header.vue';
import SiteMain from '@/views/layout/Main.vue';
import PageMenu from '@/views/pages/application/partials/Menu.vue';
import ApplicationWrapper from '@/views/pages/application/components/Wrapper.vue';
import ApplicationGrid from '@/views/pages/application/components/Grid.vue';
import ApplicationRow from '@/views/pages/application/components/Row.vue';

export default {
  components: {
    SiteHeader,
    SiteMain,
    PageMenu,
    ApplicationWrapper,
    ApplicationGrid,
    ApplicationRow
  },

  mixins: [ErrorHandling],

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
      },

      // States
      isFetched: true,
      isLoading: false,

    };
  },

  created() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.axios.get(`${this.routes.fetch}/${this.$route.params.uuid}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    },
  },

};
</script>
