import nextcloud from '@nextcloud/eslint-config';

export default [
    ...nextcloud,

    {
        languageOptions: {
            globals: {
                appVersion: 'readonly',
            },
        },

        rules: {
            'jsdoc/require-jsdoc': 'off',
            'jsdoc/tag-lines': 'off',
            'vue/first-attribute-linebreak': 'off',
            'import/extensions': 'off',
        },
    },
];
