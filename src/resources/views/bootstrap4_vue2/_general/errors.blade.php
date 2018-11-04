<template>
    <div role="alert" class="alert m-b-1 alert-danger" id="{{$fieldName}}_errors" v-if="fieldHasError('{{$fieldName}}')">
        <div v-for="error in fields['{{$fieldName}}'].errors">@{{ error }}</div>
    </div>
</template>