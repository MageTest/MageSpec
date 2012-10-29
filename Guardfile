guard 'ctags-composer', :src_path => ["src"], :vendor_path => ["vendor"] do
  watch(/^(src|spec)\/.*\.php$/)
  watch('composer.lock')
end

guard :shell do
  watch(/^(src|spec)\/.*\.php$/) do |m|
    if system('./bin/phpspec run')
      n "#{m[0]} passes", 'PHPSpec 2', :success
    else
      n "#{m[0]} causes problems", 'PHPSpec 2', :failed
    end
  end
end