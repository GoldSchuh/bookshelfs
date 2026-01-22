<template>
  <NcAppNavigation>
    <Form ref="createBook"/>
    <NcAppNavigationNew :text="translate('bookshelfs', 'Create book')" @click="createBook"/>
    <template #footer>
      <NcAppNavigationSettings>
        <NcAppNavigationNew :text="translate('bookshelfs', 'Restyle Shelf')" @click="reStyle"/>
        <NcAppNavigationNew :text="translate('bookshelfs', 'Reset Shelf')" @click="reset"/>
      </NcAppNavigationSettings>
    </template>
  </NcAppNavigation>
</template>

<script lang="ts">
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcAppNavigationNew from '@nextcloud/vue/components/NcAppNavigationNew'
import { translate } from '@nextcloud/l10n'
import Form from "./Form.vue";
import {constructBook} from "../models/Book.ts";
import {getRandomHeight, randomColor, randomPattern} from "../utils.ts";
import NcAppNavigationSpacer from '@nextcloud/vue/components/NcAppNavigationSpacer'
import NcAppNavigationSettings from '@nextcloud/vue/components/NcAppNavigationSettings'
import NcAppNavigationItem from '@nextcloud/vue/components/NcAppNavigationItem'

export default {
  components: {
    Form,
    NcAppNavigation,
    NcAppNavigationNew,
    NcAppNavigationSpacer,
    NcAppNavigationSettings,
    NcAppNavigationItem,
  },

  methods: {
    translate,
    createBook() {
      const create: any = this.$refs.createBook;
      if (!create.title) {
        create.title = 'a';
      }
      if (!create.author) {
        create.author = 'a';
      }
      if (!create.url) {
        create.url = '';
      }
      if (!create.file) {
        create.file = -1;
      }
      if (!create.colour) {
        create.colour = randomColor();
      }
      if (!create.pattern) {
        create.pattern = randomPattern();
      }
      if (!create.height) {
        create.height = getRandomHeight();
      }
      this.$emit('createBook', constructBook({ title: create.title,
        author: create.author,
        url: create.url,
        file: create.file,
        colour: create.colour,
        pattern: create.pattern,
        height: create.height,
        })
      )
      create.title = ''
      create.author = ''
      create.url = ''
      create.file = ''
      create.colour = ''
      create.pattern = ''
      create.height = ''
    },
    reStyle() {
      this.$emit('reStyle');
    },
    reset() {
      this.$emit('reset');
    }
  }
}
</script>
