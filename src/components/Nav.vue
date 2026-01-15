<template>
  <NcAppNavigation>
    <!-- Form to add a new book -->
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
    <NcAppNavigationNew :text="translate('bookshelfs', 'Create book')" @click="create"/>
  </NcAppNavigation>
</template>

<script>
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcAppNavigationNew from '@nextcloud/vue/components/NcAppNavigationNew'
import { translate } from '@nextcloud/l10n'
import {getFilePickerBuilder} from '@nextcloud/dialogs';

export default {
  name: 'Nav',

  components: {
    NcAppNavigation,
    NcAppNavigationNew,
  },

  data() {
    return {
      title: '',
      author: '',
      url: '',
      file: '',
      file_name: ''
    }
  },
  methods: {
    translate,
    create() {
      if (!this.title || !this.author) {
        this.title = 'a';
        this.author = 'a';
      }
      this.$emit('create', {
        title: this.title,
        author: this.author,
        url: this.url,
        file: this.file
      })
      this.title = ''
      this.author = ''
      this.url = ''
      this.file = ''
    },
    pickImage() {
      getFilePickerBuilder('Path to your book cover image',)
          .addMimeTypeFilter('image/jpeg')
          .addMimeTypeFilter('image/png')
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.url = selectedPaths[0]._data.id || '';
              this.url = this.url.toString()
              console.log(this.url)
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
              this.file = selectedPaths[0]._data.id || -1;
            }
          })
          .build().pick();
    }
  }
}
</script>

<style scoped lang="scss">
.create-book-form {
  margin: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
</style>
