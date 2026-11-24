<template>
  <div class="dialog" v-if="isOpen">
    <div class="dialog__inner edit-link-dialog">

      <!-- send -->
      <template v-if="!result">
        <div class="message is-left">
          <h2 class="mb-3x">Bearbeitungslink senden</h2>
          <p class="mb-4x">
            Die gesuchstellende Person erhält per E-Mail einen Link, mit dem sie das Gesuch einmal selbst anpassen kann. Nach dem Speichern ist der Link nicht mehr gültig.
            <template v-if="active">Der bestehende Link wird dabei ungültig.</template>
          </p>
          <label for="edit-link-email">E-Mail *</label>
          <input id="edit-link-email" type="email" v-model="email" :class="{ 'is-invalid': errors.email }" @input="errors.email = ''">
          <div class="edit-link-dialog__error" v-if="errors.email">{{ errors.email }}</div>
          <div class="edit-link-dialog__error" v-if="errors.general">{{ errors.general }}</div>
        </div>
        <div class="actions">
          <a href="javascript:;" :class="[isSending ? 'disabled' : '', 'btn-primary mb-3x']" @click.prevent="send()">
            {{ isSending ? 'Wird gesendet …' : 'Link senden' }}
          </a>
          <a href="javascript:;" class="btn-secondary is-outline" @click="hide()">Abbrechen</a>
        </div>
      </template>

      <!-- result -->
      <template v-else>
        <div class="message is-left">
          <h2 class="mb-3x">{{ result.mail_sent ? 'Link gesendet' : 'E-Mail konnte nicht gesendet werden' }}</h2>
          <p class="mb-4x" v-if="result.mail_sent">
            Der Link wurde an {{ sentTo }} gesendet. Er ist gültig bis {{ formatDate(result.expires_at) }}.
          </p>
          <p class="mb-4x" v-else>
            Der Link wurde erstellt, die E-Mail an {{ sentTo }} ist aber fehlgeschlagen. Kopieren Sie den Link und senden Sie ihn selbst, z.B. aus Outlook. Er ist gültig bis {{ formatDate(result.expires_at) }}.
          </p>
          <label for="edit-link-url">Link</label>
          <input id="edit-link-url" ref="url" type="text" :value="result.url" readonly @focus="$event.target.select()">
          <p class="mt-2x edit-link-dialog__hint">Der Link wird nur jetzt angezeigt. Bei Bedarf können Sie später einen neuen Link senden.</p>
        </div>
        <div class="actions">
          <a href="javascript:;" class="btn-primary mb-3x" @click.prevent="copy()">{{ isCopied ? 'Kopiert' : 'Link kopieren' }}</a>
          <a href="javascript:;" class="btn-secondary is-outline" @click="hide()">Schliessen</a>
        </div>
      </template>

    </div>
  </div>
</template>
<script>
export default {
  props: {
    uuid: { type: String, required: true },
    defaultEmail: { type: String, default: '' },
    active: { type: Boolean, default: false },
  },

  data() {
    return {
      isOpen: false,
      isSending: false,
      isCopied: false,
      email: '',
      sentTo: '',
      result: null,
      errors: { email: '', general: '' },
    };
  },

  created() {
    this.onEscape = (e) => {
      if (this.isOpen && e.keyCode === 27) {
        this.hide();
      }
    };
    document.addEventListener('keydown', this.onEscape);
  },

  beforeDestroy() {
    document.removeEventListener('keydown', this.onEscape);
  },

  methods: {
    show() {
      this.email = this.defaultEmail;
      this.result = null;
      this.isCopied = false;
      this.errors = { email: '', general: '' };
      this.isOpen = true;
    },

    hide() {
      this.isOpen = false;
    },

    send() {
      if (this.isSending) {
        return;
      }
      this.isSending = true;
      this.errors = { email: '', general: '' };

      this.axios.post(`/api/application/edit-link/${this.uuid}`, {
        email: this.email,
      })
      .then(response => {
        this.isSending = false;
        this.sentTo = this.email;
        this.result = response.data;
        this.$emit('issued', response.data);
      })
      .catch(error => {
        this.isSending = false;
        const data = error.response ? error.response.data : null;
        if (data && data.errors && data.errors.email) {
          this.errors.email = data.errors.email[0].error;
        }
        else {
          this.errors.general = (data && data.message) || 'Der Link konnte nicht erstellt werden. Bitte versuchen Sie es erneut.';
        }
      });
    },

    copy() {
      const done = () => { this.isCopied = true; };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(this.result.url).then(done);
        return;
      }
      this.$refs.url.select();
      document.execCommand('copy');
      done();
    },

    formatDate(value) {
      return new Date(value).toLocaleString('de-CH', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' Uhr';
    },
  },
};
</script>
<style scoped>
.edit-link-dialog .message label {
  display: block;
  margin-bottom: 4px;
}
.edit-link-dialog .message input {
  background: #fff;
  border: 0;
  color: #000;
  font: inherit;
  font-size: 15px;
  padding: 8px;
  width: 100%;
}
.edit-link-dialog .message input.is-invalid {
  outline: 2px solid #ffb3b3;
}
.edit-link-dialog__error {
  margin-top: 4px;
  color: #ffd1d1;
}
.edit-link-dialog__hint {
  font-size: 14px;
  opacity: .85;
}
</style>
