<template>
  <nav class="page-menu mb-15x">
    <ul>
      <li>
        <a href="javascript:history.back();" title="Zurück">
          <span>Zurück</span>
        </a>
      </li>
      <li>
        <a href="">
          <span>Bearbeiten</span>
        </a>
      </li>
      <li>
        <router-link :to="{name: 'application-comments', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Kommentar</span>
        </router-link>
      </li>
      <li>
        <a href="">
          <span>Protokoll</span>
        </a>
      </li>
      <li>
        <a href="">
          <span>Archivieren</span>
        </a>
      </li>
      <li>
        <a href="">
          <span>Löschen</span>
        </a>
      </li>
    </ul>
    <slot />
  </nav>
</template>
<script>
export default {
  props: {
    type: String,
    uuid: String
  },
}
</script>