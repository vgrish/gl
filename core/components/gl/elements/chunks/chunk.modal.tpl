<div style="display: none;">
    <div class="gl-modal">
        <div class="gl-default">
            <h5>Ваш город — <b>[[+gl.current.city.name_ru]]</b>?</h5>

            <span class="btn-yes">Да </span>
            <span class="btn-change">Изменить </span>

            <ul class="gl-default-list" style="display: none;">
                [[!gl.location?
                &class=`[[+class]]`
                &where=`{"name_ru":"[[+gl.current.city.name_ru]]"}`
                ]]
            </ul>

        </div>

        <div class="gl-change-select" style="display: none;">
            <h5>Выберите Ваш город</h5>

            <p class="desc">
                Введите название вашего города
            </p>

            <div class="gl-change-input-block">
                <select name="location" value="" style="width: 100%" class="select2 " multiple>
                </select>
            </div>

            <ul class="gl-change-list">
                [[!gl.location?
                &class=`[[+class]]`
                ]]
            </ul>

        </div>

    </div>
</div>