<template>

</template>

<script>
import NcRichContenteditable from '@nextcloud/vue/components/NcRichContenteditable'

import { delay } from '../utils.js'

export default {
  name: 'MyMainContent',

  components: {
    NcRichContenteditable,
  },

  // props: {
  //   note: {
  //     type: Object,
  //     required: true,
  //   },
  // },
  //
  // data() {
  //   return {
  //   }
  // },
  //
  // computed: {
  // },
  //
  // watch: {
  // },
  //
  // mounted() {
  // },
  //
  // beforeDestroy() {
  // },
  //
  // methods: {
  //   onUpdateValue(newValue) {
  //     delay(() => {
  //       this.$emit('edit-note', this.note.id, newValue)
  //     }, 2000)()
  //   },
  // },
}
</script>

<style scoped lang="scss">
.main-content {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  .content-editable {
    min-width: 600px;
    min-height: 200px;
  }
}
</style>
