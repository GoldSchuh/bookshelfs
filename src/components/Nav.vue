<!--
  - SPDX-FileCopyrightText: 2026 Kars van Velzen
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
  <NcAppNavigation>
    <Form ref="createBook"/>
    <NcButton
        class="from-file"
        type="secondary"
        @click="addBookFromFile"
    >
      <template #icon>
        <span class="icon-upload"/>
      </template>
      {{ translate('bookshelfs', 'Add book from file') }}
    </NcButton>
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
import NcButton from '@nextcloud/vue/components/NcButton'
import { getFilePickerBuilder } from '@nextcloud/dialogs'
import { translate } from '@nextcloud/l10n'
import Form from "./Form.vue";
import {constructBook} from "../models/Book.ts";
import {getRandomHeight, randomColour, randomPattern} from "../utils.ts";
import NcAppNavigationSettings from '@nextcloud/vue/components/NcAppNavigationSettings'

export default {
  components: {
    Form,
    NcAppNavigation,
    NcAppNavigationNew,
    NcButton,
    NcAppNavigationSettings,
  },

  methods: {
    translate,
    addBookFromFile() {
      getFilePickerBuilder(translate('bookshelfs', 'Path to your e-book file'))
          .addMimeTypeFilter('application/epub+zip')
          .addMimeTypeFilter('application/pdf')
          .addButton({
            label: translate('bookshelfs', 'Choose'),
            variant: 'primary',
            callback: (selectedPaths) => {
              const node = selectedPaths?.[0] as any
              const rawId = node?.fileid ?? node?._data?.id ?? (node?.id !== undefined && node?.id !== null ? node.id : undefined)
              const fileId = rawId !== undefined && rawId !== null && Number(rawId) > 0 ? Number(rawId) : undefined
              if (fileId !== undefined) {
                this.$emit('createBookFromFile', fileId)
              }
            }
          })
          .build().pick();
    },
    createBook() {
      const create = this.$refs.createBook as InstanceType<typeof Form>;
      if (!create.title) {
        create.title = '';
      }
      if (!create.author) {
        create.author = '';
      }
      if (!create.url) {
        create.url = '-1';
      }
      if (!create.file) {
        create.file = -1;
      }
      if (!create.colour) {
        create.colour = randomColour();
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
        file: Number(create.file) || -1,
        colour: create.colour,
        pattern: Number(create.pattern) || 0,
        height: Number(create.height) || getRandomHeight(),
        })
      )
      create.title = ''
      create.author = ''
      create.url = ''
      create.file = null
      create.colour = ''
      create.pattern = null
      create.height = null
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
