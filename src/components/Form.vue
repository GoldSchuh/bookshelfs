<template>
    <div class="create-book-form">
      <input
          v-model="title"
          type="text"
          :placeholder="translate('bookshelfs', 'Title')"
          class="input-title"
      />
      <input
          v-model="author"
          type="text"
          :placeholder="translate('bookshelfs', 'Author')"
          class="input-author"
      />
      <input
          v-model="colour"
          type="text"
          :placeholder="translate('bookshelfs', 'Colour')"
          class="input-author"
      />
      <input
          v-model="pattern"
          type="text"
          :placeholder="translate('bookshelfs', 'Pattern ID')"
          class="input-author"
      />
      <input
          v-model="height"
          type="text"
          :placeholder="translate('bookshelfs', 'Height')"
          class="input-author"
      />
      <input
          v-model="url"
          type="text"
          :placeholder="translate('bookshelfs', 'Picture ID')"
      />
      <button
          type="button"
          title="'Pick a local image'"
          @click="pickImage"
      >
        <span class="icon-category-multimedia"></span>
      </button>
      <input
          v-model="file"
          type="text"
          :placeholder="translate('bookshelfs', 'File ID')"
      />
      <button
          type="button"
          title="Pick a local file"
          @click="pickEbook"
      >
        <span class="icon-category-files"></span>
      </button>
    </div>
</template>

<script lang="ts">

import { translate } from '@nextcloud/l10n'
import {getFilePickerBuilder} from '@nextcloud/dialogs';

export default {
  data() {
    return {
      title: '',
      author: '',
      url: '',
      file: '',
      colour: '',
      pattern: '',
      height: '',
    }
  },
  methods: {
    translate,
    pickImage() {
      getFilePickerBuilder('Path to your book cover image',)
          .addMimeTypeFilter('image/jpeg')
          .addMimeTypeFilter('image/png')
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.url = (selectedPaths?.[0] as any)?._data?.id.toString() || '';
            }
          })
          .build().pick();
    },
    pickEbook() {
      getFilePickerBuilder('Path to your book',)
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.file = (selectedPaths?.[0] as any)?._data.id || -1;
            }
          })
          .build().pick();
    }
  }
}
</script>

<style scoped lang="scss">
.form {
  margin: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
</style>
