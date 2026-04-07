(function($) {
    const namespace = "yearpicker";

    // Define the default settings inside the plugin
    let defaults = {
        year: null,
        startYear: null,
        endYear: null,
        itemTag: "li",
        selectedClass: "selected",
        disabledClass: "disabled",
        hideClass: "hide",
        template: `<div class="yearpicker-container">
        <div class="yearpicker-header">
            <div class="yearpicker-prev" data-view="yearpicker-prev">&lsaquo;</div>
            <div class="yearpicker-current" data-view="yearpicker-current">SelectedYear</div>
            <div class="yearpicker-next" data-view="yearpicker-next">&rsaquo;</div>
        </div>
        <div class="yearpicker-body">
            <ul class="yearpicker-year" data-view="years">
            </ul>
        </div>
    </div>`
    ,
        onShow: null,
        onHide: null,
        onSelect: null,
        onChange: null,
    };

    // Plugin constructor
    function YearPicker(element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.yearpicker.defaults, options);
        this.$template = $(this.options.template);
        this.show = false;
        this.year = this.options.year;
        this.viewYear = this.options.year || new Date().getFullYear();
        this.init();
    }

    // Define methods directly on the YearPicker prototype
    $.extend(YearPicker.prototype, {
        init: function() {
            this.buildPicker();
            this.bindEvents();
            this.renderYear();
        },

        buildPicker: function() {
            const $this = this;
            $this.$element.after($this.$template);
            $this.$template.addClass($this.options.hideClass);
        },

        bindEvents: function() {
            const $this = this;
            $this.$element.on('keydown.' + namespace, function(e) {
                e.preventDefault();
            });
        
            // $this.$element.on('focus.' + namespace + ' click.' + namespace, function(e) {
            //     e.stopPropagation();
            //     $this.showView();
            // });

            $this.$element.on('click.' + namespace, function(e) {
                e.stopPropagation();
                // Toggle the view instead of just showing it
                $this.toggleView();
            });

            $(document).on('click.' + namespace, function(e) {
                if (!$this.$template.is(e.target) && $this.$template.has(e.target).length === 0 && !$this.$element.is(e.target)) {
                    $this.hideView();
                }
            });

            // $(document).on('click.' + namespace, function(e) {
            //     if (!$this.$template.is(e.target) && $this.$template.has(e.target).length === 0 && !$this.$element.is(e.target)) {
            //         $this.hideView();
            //     }
            // });
            
            // $this.$element.on('focus.' + namespace + ' click.' + namespace, function(e) {
            //     e.stopPropagation();
            //     $this.showView();
            // });
        
            // $(document).on('click.' + namespace, function(e) {
            //     if (!$this.$template.is(e.target) && $this.$template.has(e.target).length === 0 && !$this.$element.is(e.target)) {
            //         $this.hideView();
            //     }
            // });
        
            $this.$template.on('click.' + namespace, '.yearpicker-prev, .yearpicker-next, .yearpicker-year li', function(e) {
                e.stopPropagation();
                const $target = $(e.target);
        
                if ($target.hasClass('yearpicker-prev')) {
                    $this.viewYear -= 12;
                }
                else if ($target.hasClass('yearpicker-next')) {
                    $this.viewYear += 12;
                }
                else if ($target.closest('li[data-year]').length) {
                    const selectedYear = $target.closest('li').data('year');
                    if (!selectedYear) {
                        return;
                    }
                    $this.selectYear(selectedYear);
                    $this.hideView();
                }
        
                $this.renderYear();
            });
        },
        

        renderYear: function() {
            const $this = this;
            let yearList = '';
            let startYear = $this.viewYear - 5;
            let endYear = $this.viewYear + 6;
        
            for (let year = startYear; year <= endYear; year++) {
                let classes = year === $this.year ? $this.options.selectedClass : '';
                yearList += `<${$this.options.itemTag} class='${classes}' data-year='${year}'>${year}</${$this.options.itemTag}>`;
            }
        
            const $yearBody = $this.$template.find('.yearpicker-body ul');
            $yearBody.html(yearList);
        
            const $currentYearDisplay = $this.$template.find('.yearpicker-current');
            $currentYearDisplay.text(startYear + '-' + endYear);
        },

        showView: function() {
            if(!this.show) {
                this.show = true;
                this.$template.removeClass(this.options.hideClass);
                if ($.isFunction(this.options.onShow)) {
                    this.options.onShow.call(this, this.year);
                }
            }
        },

        hideView: function() {
            if(this.show) {
                this.show = false;
                this.$template.addClass(this.options.hideClass);
                if ($.isFunction(this.options.onHide)) {
                    this.options.onHide.call(this, this.year);
                }
            }
        },

        toggleView:  function() {
            if(this.show) {
                this.hideView();
            } else {
                this.showView();
            }
        },

        selectYear: function(year) {
            this.year = year;
            this.$element.val(year);
        
            if ($.isFunction(this.options.onChange)) {
                this.options.onChange.call(this, year);
            }
        
            if ($.isFunction(this.options.onSelect)) {
                this.options.onSelect.call(this, year);
            }
        },
    });

    $.fn.yearpicker = function(options) {
        return this.each(function() {
            var $this = $(this);
            var instance = $this.data(namespace);
            if (!instance) {
                $this.data(namespace, new YearPicker(this, options));
            }
        });
    };

    // Load default options from an external JSON file
    $.getJSON('/Options.json', function(data) {
        if (!data) {
            console.error('Options could not be loaded');
            return;
        }
        defaults = $.extend(defaults, data);
        // Initialize yearpickers on document ready
        $(document).ready(function() {
            $('.yearpicker').yearpicker();
        });
    });

    // Default settings outside the load to ensure defaults are set before initialization
    $.fn.yearpicker.defaults = defaults;

})(jQuery);
