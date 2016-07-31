<div style="display: none;">
    <div class="gl-modal uk-modal">
        <div class="gl-default uk-modal-dialog">

            <h5>Ваш город —<b>[[!+gl.selected.city.name_ru]]</b>?
            </h5>

            <span class="btn-yes">Да </span>
            <span class="btn-change">Изменить </span>

            <ul class="gl-default-list" style="display: none;">
                [[!gl.location?
                &limit=`1`
                &class=`[[+class]]`
                &where=`{"name_ru":"[[!+gl.selected.city.name_ru]]"}`
                ]]
            </ul>

        </div>

        <div class="gl-change-select uk-modal-dialog" style="display: none;">
            <h5>Выберите Ваш город</h5>

            <p class="desc">
                Введите название вашего города
            </p>

            <div class="gl-change-input-block">
                <select name="location" value="" style="width: 100%" class="select2 " multiple>
                </select>
                <span class="gl-select2-container"></span>
            </div>

            <ul class="gl-change-list">
                [[!gl.location?
                &class=`[[+class]]`
                &where=`{"default":0}`
                ]]
            </ul>

        </div>

    </div>
</div>