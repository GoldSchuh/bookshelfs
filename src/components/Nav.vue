<template>
  <NcAppNavigation>
    <Form ref="createBook"/>
    <NcAppNavigationNew :text="translate('bookshelfs', 'Create book')" @click="create"/>
  </NcAppNavigation>
</template>

<script>
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcAppNavigationNew from '@nextcloud/vue/components/NcAppNavigationNew'
import { translate } from '@nextcloud/l10n'
import Form from "./Form.vue";

export default {
  name: 'Nav',

  components: {
    Form,
    NcAppNavigation,
    NcAppNavigationNew,
  },

  methods: {
    translate,
    create() {
      const create = this.$refs.createBook;
      if (!create.title || !create.author) {
        create.title = 'a';
        create.author = 'a';
      }
      this.$emit('create', {
        title: create.title,
        author: create.author,
        url: create.url,
        file: create.file
      })
      create.title = ''
      create.author = ''
      create.url = ''
      create.file = ''
    },
  }
}
</script>
