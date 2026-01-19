<template>
  <NcAppNavigation>
    <Form ref="createBook"/>
    <NcAppNavigationNew :text="translate('bookshelfs', 'Create book')" @click="createBook"/>
  </NcAppNavigation>
</template>

<script lang="ts">
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcAppNavigationNew from '@nextcloud/vue/components/NcAppNavigationNew'
import { translate } from '@nextcloud/l10n'
// @ts-ignore
import Form from "./Form.vue";
import {constructBook} from "../models/Book.ts";

export default {
  name: 'Nav',

  components: {
    Form,
    NcAppNavigation,
    NcAppNavigationNew,
  },

  methods: {
    getRandomHeight(min = 220, max = 290) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    randomPattern() {
      return this.getRandomHeight(0, 3);
    },
    randomColor() {
      const availableColors = [
        "maroon",
        "darkgreen",
        "darkolivegreen",
        "brown",
        "saddlebrown",
        "sienna",
        "midnightblue",
      ];
      return availableColors[Math.floor(Math.random() * availableColors.length)];
    },
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
        create.colour = this.randomColor();
      }
      if (!create.pattern) {
        create.pattern = this.randomPattern();
      }
      if (!create.height) {
        create.height = this.getRandomHeight();
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
  }
}
</script>
