<template>
  <NcAppSidebar v-if="open" @close="open = false">
      <div v-if="selected!==null">
        <h3>{{ selected.title }}</h3>
        <p>{{ selected.author }}</p>
      </div>
    </NcAppSidebar>
</template>

<script>
import IconMagnify from 'vue-material-design-icons/Magnify.vue'
import IconCogOutline from 'vue-material-design-icons/CogOutline.vue'
import IconShareVariantOutline from 'vue-material-design-icons/ShareVariantOutline.vue'
import NcAppSidebarTab from  '@nextcloud/vue/components/NcAppSidebarTab'
import NcAppSidebar from  '@nextcloud/vue/components/NcAppSidebar'

export default {
  name: 'Sidebar',
  components: {
    IconMagnify,
    IconCogOutline,
    IconShareVariantOutline,
    NcAppSidebarTab,
    NcAppSidebar,
  },
  data() {
    return {
      open: false,
      selected: null
    }
  },
  methods: {
    select(book) {
      this.selected = book
      this.open = true
    }
  }
}
</script>

<style scoped>

</style>
