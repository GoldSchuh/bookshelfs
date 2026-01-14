<template>
  <NcAppNavigation>
    <!-- Form to add a new book -->
    <div class="add-book-form">
      <input
          v-model="newBookTitle"
          type="text"
          placeholder="Enter Book Title"
          class="input-title"
      />
      <input
          v-model="newBookAuthor"
          type="text"
          placeholder="Enter Book Author"
          class="input-author"
      />
      <input
          v-model="newBookUrl"
          type="text"
          placeholder="Enter URL or select from your Nextcloud instance on the right"
      />
      <button
          type="button"
          :title="'Pick a local image'"
          @click="pickImage"
      >
        <span class="icon-category-multimedia"></span>
      </button>
      <button @click="addBook" class="book-add">Add</button>
    </div>
  </NcAppNavigation>
</template>

<script>
import FileExportOutlineIcon from 'vue-material-design-icons/FileExportOutline.vue'
import PlusIcon from 'vue-material-design-icons/Plus.vue'
import TrashCanOutlineIcon from 'vue-material-design-icons/TrashCanOutline.vue'
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcAppNavigationItem from '@nextcloud/vue/components/NcAppNavigationItem'
import NcActionButton from '@nextcloud/vue/components/NcActionButton'
import NcAppNavigationNewItem from '@nextcloud/vue/components/NcAppNavigationNewItem'
import { translate } from '@nextcloud/l10n'
import {getFilePickerBuilder} from '@nextcloud/dialogs';

export default {
  name: 'Nav',

  components: {
    NcAppNavigation,
    NcEmptyContent,
    NcAppNavigationItem,
    NcActionButton,
    NcAppNavigationNewItem,
    FileExportOutlineIcon,
    PlusIcon,
    TrashCanOutlineIcon,
  },

  data() {
    return {
      newBookTitle: '',
      newBookAuthor: '',
      newBookUrl: '',
      newBookFile: -1,
    }
  },
  methods: {
    addBook() {
      if (!this.newBookTitle || !this.newBookAuthor) {
        this.newBookTitle = 'a';
        this.newBookAuthor = 'a';
      }
      this.$emit('add-book', {
        title: this.newBookTitle,
        author: this.newBookAuthor,
        url: this.newBookUrl
      })
      this.newBookTitle = ''
      this.newBookAuthor = ''
      this.newBookUrl = ''
    },
    pickImage() {
      getFilePickerBuilder('Path to your book cover image',)
          .addMimeTypeFilter('image/jpeg')
          .addMimeTypeFilter('image/png')
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.newBookUrl = selectedPaths[0]._data.attributes.filename || '';
            }
          })
          .build().pick();
    }
  }
}
</script>

<style scoped lang="scss">
/* Input */
//.add-book-form {
//  margin-bottom: 10px;
//  display: flex;
//  gap: 5px;
//}
//.input-title,
//.input-author {
//  padding: 8px;
//  font-size: 14px;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  width: 20vw;
//  max-width: 160px;
//  max-height: 16px;
//}
//.book-add {
//  color: white;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  cursor: pointer;
//  max-height: 16px;
//}
//.book-add:hover {
//  background-color: #45a049;
//}
</style>
